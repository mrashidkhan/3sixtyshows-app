<?php

namespace App\Http\Controllers;

use App\Models\Show;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\TicketHold;
use App\Services\SeatsioService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * BookingController
 *
 * Handles two separate booking flows:
 *
 * 1. GENERAL ADMISSION  — existing flow using TicketType + TicketHold
 * 2. RESERVED (seats.io) — new flow using seats.io widget + hold token
 *
 * The entry point is show() which renders the public booking page.
 * The flow diverges at initiate() based on $show->ticketing_mode.
 */
class BookingController extends Controller
{
    public function __construct(private SeatsioService $seatsio) {}

    // =========================================================================
    // PUBLIC BOOKING PAGE
    // =========================================================================

    /**
     * Show the booking page for a given show.
     * For reserved shows this view embeds the seats.io widget.
     * For GA shows it shows the existing ticket quantity selector.
     */
    public function show(Show $show)
    {
        if (!$show->is_active) {
            abort(404);
        }

        // Redirect shows go straight to external URL
        if ($show->redirect && $show->redirect_url) {
            return redirect($show->redirect_url);
        }

        // seats.io reserved / mixed shows
        if ($show->usesSeatsIo()) {
            if (!$show->isSeatsIoReady()) {
                return view('booking.unavailable', compact('show'));
            }
            if (!$show->isSaleOpen()) {
                return view('booking.not-on-sale', compact('show'));
            }

            // Generate a hold token for this user session (server-side)
            // The token is passed to the JS widget via the Blade view.
            $holdToken = $this->seatsio->createHoldToken($show, 15);
            session(['seatsio_hold_token_' . $show->id => $holdToken]);

            // Get ticket types mapped to seats.io categories for price display
            $ticketTypes = $show->activeTicketTypes()->get();
            $seatsioPublicKey = $show->seatsio_public_key; // uses model accessor fallback

            return view('booking.reserved', compact('show', 'holdToken', 'ticketTypes', 'seatsioPublicKey'));
        }

        // General Admission (existing flow)
        $ticketTypes = $show->getAvailableTicketTypes();
        return view('booking.general', compact('show', 'ticketTypes'));
    }

    // =========================================================================
    // INITIATE BOOKING (POST from booking page)
    // =========================================================================

    /**
     * Called by the "Book Now" form POST on both GA and reserved pages.
     *
     * For GA: receives ticket_types[] + quantities
     * For reserved: receives hold_token + selected_seats[] (JSON from widget)
     */
    public function initiate(Request $request, Show $show)
    {
        if ($show->usesSeatsIo()) {
            return $this->initiateReserved($request, $show);
        }
        return $this->initiateGA($request, $show);
    }

    // =========================================================================
    // GENERAL ADMISSION FLOW (unchanged from existing logic)
    // =========================================================================

    private function initiateGA(Request $request, Show $show)
    {
        $request->validate([
            'ticket_types'              => 'required|array|min:1',
            'ticket_types.*.id'         => 'required|exists:ticket_types,id',
            'ticket_types.*.quantity'   => 'required|integer|min:1|max:20',
            'customer_name'             => 'required|string|max:255',
            'customer_email'            => 'required|email|max:255',
            'customer_phone'            => 'nullable|string|max:30',
        ]);

        DB::beginTransaction();
        try {
            $totalAmount   = 0;
            $totalTickets  = 0;
            $ticketBreakdown = [];

            foreach ($request->ticket_types as $item) {
                $ticketType = TicketType::findOrFail($item['id']);
                $qty = (int) $item['quantity'];

                // Capacity check
                $available = $show->getAvailableCapacityForTicketType($ticketType->id);
                if ($available < $qty) {
                    throw new \Exception("Only $available tickets left for {$ticketType->name}.");
                }

                $lineTotal = $ticketType->price * $qty;
                $totalAmount  += $lineTotal;
                $totalTickets += $qty;
                $ticketBreakdown[] = [
                    'ticket_type_id'   => $ticketType->id,
                    'ticket_type_name' => $ticketType->name,
                    'quantity'         => $qty,
                    'unit_price'       => $ticketType->price,
                    'total_price'      => $lineTotal,
                ];

                // Place temporary hold
                TicketHold::holdTickets($show->id, $ticketType->id, $qty, session()->getId(), 30);
            }

            // Fee calculation
            $serviceFee    = max($totalAmount * 0.03, 2.00);
            $processingFee = $totalTickets * 1.50;
            $grandTotal    = $totalAmount + $serviceFee + $processingFee;

            // Create pending booking
            $booking = Booking::create([
                'show_id'          => $show->id,
                'user_id'          => auth()->id(),
                'booking_number'   => 'BK-' . strtoupper(Str::random(8)),
                'customer_name'    => $request->customer_name,
                'customer_email'   => $request->customer_email,
                'customer_phone'   => $request->customer_phone,
                'number_of_tickets'=> $totalTickets,
                'total_amount'     => $totalAmount,
                'service_fee'      => $serviceFee,
                'processing_fee'   => $processingFee,
                'grand_total'      => $grandTotal,
                'ticket_breakdown' => $ticketBreakdown,
                'booking_date'     => now(),
                'expires_at'       => now()->addMinutes(30),
                'status'           => Booking::STATUS_PENDING,
                'payment_status'   => Booking::PAYMENT_PENDING,
                'booking_mode'     => 'general_admission',
            ]);

            // Create booking items
            foreach ($request->ticket_types as $item) {
                $ticketType = TicketType::find($item['id']);
                $qty = (int) $item['quantity'];
                BookingItem::create([
                    'booking_id'     => $booking->id,
                    'ticket_type_id' => $ticketType->id,
                    'quantity'       => $qty,
                    'unit_price'     => $ticketType->price,
                    'total_price'    => $ticketType->price * $qty,
                    'item_type'      => 'general_admission',
                ]);
            }

            DB::commit();

            // Store booking id in session for payment page
            session(['pending_booking_id' => $booking->id]);

            return redirect()->route('booking.payment', $booking->booking_number);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('GA booking initiate failed: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // =========================================================================
    // RESERVED SEATING FLOW (seats.io)
    // =========================================================================

    private function initiateReserved(Request $request, Show $show)
    {
        $request->validate([
            'hold_token'     => 'required|string',
            'selected_seats' => 'required|string', // JSON string from widget
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:30',
        ]);

        // Verify hold token matches what we issued for this session
        $sessionToken = session('seatsio_hold_token_' . $show->id);
        if ($sessionToken !== $request->hold_token) {
            return back()->withErrors(['error' => 'Your seat selection has expired. Please select seats again.']);
        }

        // Decode selected seats from widget JSON
        $selectedSeats = json_decode($request->selected_seats, true);
        if (empty($selectedSeats)) {
            return back()->withErrors(['error' => 'No seats selected. Please select at least one seat.']);
        }

        DB::beginTransaction();
        try {
            $totalAmount  = 0;
            $totalTickets = count($selectedSeats);

            // Build booking items from selected seat objects
            // Each seat object from the widget has: id (objectId), label, categoryLabel, pricing
            $itemsData = [];
            foreach ($selectedSeats as $seat) {
                // Find the matching ticket type by seatsio_category_key
                $ticketType = $show->activeTicketTypes()
                    ->where('seatsio_category_key', $seat['category']['key'] ?? null)
                    ->first();

                $price = $ticketType ? (float) $ticketType->price : 0.00;
                $totalAmount += $price;

                $itemsData[] = [
                    'ticket_type_id'    => $ticketType?->id,
                    'seatsio_object_id' => $seat['id'] ?? $seat['label'] ?? null,
                    'seat_section'      => $seat['labels']['section'] ?? null,
                    'seat_row'          => $seat['labels']['parent'] ?? null,
                    'seat_number_label' => $seat['labels']['own'] ?? null,
                    'seat_identifier'   => $seat['label'] ?? null,
                    'category_label'    => $seat['categoryLabel'] ?? null,
                    'unit_price'        => $price,
                    'total_price'       => $price,
                    'quantity'          => 1,
                    'item_type'         => 'reserved_seat',
                ];
            }

            // Fees
            $serviceFee    = max($totalAmount * 0.03, 2.00);
            $processingFee = $totalTickets * 1.50;
            $grandTotal    = $totalAmount + $serviceFee + $processingFee;

            // Create pending booking
            $booking = Booking::create([
                'show_id'            => $show->id,
                'user_id'            => auth()->id(),
                'booking_number'     => 'BK-' . strtoupper(Str::random(8)),
                'customer_name'      => $request->customer_name,
                'customer_email'     => $request->customer_email,
                'customer_phone'     => $request->customer_phone,
                'number_of_tickets'  => $totalTickets,
                'total_amount'       => $totalAmount,
                'service_fee'        => $serviceFee,
                'processing_fee'     => $processingFee,
                'grand_total'        => $grandTotal,
                'ticket_breakdown'   => $itemsData,
                'booking_date'       => now(),
                'expires_at'         => now()->addMinutes(30),
                'status'             => Booking::STATUS_PENDING,
                'payment_status'     => Booking::PAYMENT_PENDING,
                'booking_mode'       => $show->ticketing_mode,
                'seatsio_hold_token' => $request->hold_token,
            ]);

            // Create one booking item per seat
            foreach ($itemsData as $itemData) {
                BookingItem::create(array_merge($itemData, ['booking_id' => $booking->id]));
            }

            DB::commit();

            session(['pending_booking_id' => $booking->id]);

            return redirect()->route('booking.payment', $booking->booking_number);

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Reserved booking initiate failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Booking failed. Please try again.']);
        }
    }

    // =========================================================================
    // PAYMENT CONFIRMATION (called after PayPal success)
    // =========================================================================

    /**
     * Called by your existing PayPal webhook/capture handler AFTER
     * payment is confirmed. Confirms the booking in seats.io and generates tickets.
     *
     * Add this call inside your existing PayPal success handler:
     *   app(BookingController::class)->confirmBooking($booking, $paypalData);
     */
    public function confirmBooking(Booking $booking, array $paypalData = []): void
    {
        DB::transaction(function () use ($booking, $paypalData) {
            $show = $booking->show;

            // For seats.io reserved shows: confirm the seats via API
            if ($show->usesSeatsIo() && $booking->seatsio_hold_token) {
                $objectIds = $booking->bookingItems()
                    ->whereNotNull('seatsio_object_id')
                    ->pluck('seatsio_object_id')
                    ->toArray();

                $result = $this->seatsio->bookSeats(
                    $show,
                    $objectIds,
                    $booking->seatsio_hold_token,
                    $booking->booking_number
                );

                $booking->update(['seatsio_order_id' => $result->id ?? null]);
            }

            // Update booking status
            $booking->update([
                'status'          => Booking::STATUS_CONFIRMED,
                'payment_status'  => Booking::PAYMENT_COMPLETED,
                'confirmed_at'    => now(),
                'paid_at'         => now(),
                'payment_method'  => $paypalData['method'] ?? 'paypal',
                'payment_reference' => $paypalData['reference'] ?? null,
                'paypal_payer_id' => $paypalData['payer_id'] ?? null,
            ]);

            // Generate tickets
            $this->generateTickets($booking);

            // Clear session hold token
            session()->forget('seatsio_hold_token_' . $show->id);

            Log::info('Booking confirmed', [
                'booking_number' => $booking->booking_number,
                'show_id'        => $show->id,
            ]);
        });
    }

    // =========================================================================
    // CANCELLATION
    // =========================================================================

    public function cancel(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            // Release seats in seats.io if applicable
            $this->seatsio->releaseBookingSeats($booking);

            $booking->update([
                'status'         => Booking::STATUS_CANCELLED,
                'payment_status' => Booking::PAYMENT_FAILED,
            ]);

            // Cancel all associated tickets
            Ticket::where('booking_id', $booking->id)->update(['status' => Ticket::STATUS_CANCELLED]);
        });
    }

    // =========================================================================
    // HOLD TOKEN REFRESH (AJAX endpoint)
    // =========================================================================

    /**
     * Called via AJAX from the booking page JS to extend the hold token
     * before it expires (every 10 minutes on a 15-minute token).
     */
    public function refreshHoldToken(Request $request, Show $show)
    {
        $request->validate(['hold_token' => 'required|string']);

        $sessionToken = session('seatsio_hold_token_' . $show->id);
        if ($sessionToken !== $request->hold_token) {
            return response()->json(['success' => false, 'message' => 'Invalid token'], 403);
        }

        try {
            $this->seatsio->refreshHoldToken($request->hold_token, 15);
            return response()->json(['success' => true, 'expires_in_minutes' => 15]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Could not refresh token'], 500);
        }
    }

    // =========================================================================
    // PRIVATE: Generate tickets after confirmed payment
    // =========================================================================

    private function generateTickets(Booking $booking): void
    {
        foreach ($booking->bookingItems as $item) {
            for ($i = 0; $i < $item->quantity; $i++) {
                Ticket::create([
                    'show_id'           => $booking->show_id,
                    'user_id'           => $booking->user_id,
                    'booking_id'        => $booking->id,
                    'ticket_type_id'    => $item->ticket_type_id,
                    'ticket_number'     => 'TK-' . strtoupper(Str::random(10)),
                    'price'             => $item->unit_price,
                    'status'            => Ticket::STATUS_ACTIVE,
                    'ticket_mode'       => $item->item_type === 'reserved_seat'
                                            ? Ticket::MODE_ASSIGNED_SEAT
                                            : Ticket::MODE_GENERAL_ADMISSION,
                    'seat_identifier'   => $item->seat_identifier,
                    'seatsio_object_id' => $item->seatsio_object_id,
                    'seat_section'      => $item->seat_section,
                    'seat_row'          => $item->seat_row,
                    'seat_label'        => $item->seat_identifier,
                    'purchased_date'    => now(),
                ]);
            }
        }
    }
}
