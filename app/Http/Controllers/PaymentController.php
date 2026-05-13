<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * PaymentController
 *
 * Handles the PayPal checkout page and post-payment landing pages
 * for the seats.io reserved seating flow (BookingController).
 *
 * The GA flow (GeneralAdmissionController) manages its own PayPal
 * redirect and capture — this controller is for reserved/mixed shows.
 *
 * Routes served:
 *   GET  /booking/{bookingNumber}/payment  → show()
 *   GET  /payment/success/{booking}        → success()
 *   GET  /payment/cancel/{booking}         → cancel()
 *   GET  /payment/failed/{booking}         → failed()
 *   GET  /my-bookings                      → myBookings()
 *   GET  /bookings/{booking}               → showBooking()
 *   POST /bookings/{booking}/cancel        → cancelBooking()
 */
class PaymentController extends Controller
{
    // -------------------------------------------------------------------------
    // Payment page — shown after BookingController creates a pending booking
    // -------------------------------------------------------------------------

    /**
     * Show the PayPal checkout page for a booking.
     * Called by: GET /booking/{bookingNumber}/payment
     */
    public function show($bookingNumber)
    {
        $booking = Booking::with(['show', 'show.venue', 'bookingItems.ticketType'])
            ->where(function ($q) {
                $q->where('user_id', Auth::id())
                  ->orWhere('customer_email', Auth::user()->email);
            })
            ->where('booking_number', $bookingNumber)
            ->where('status', 'pending')
            ->firstOrFail();

        // If booking has expired, release it and redirect to show page
        if ($booking->expires_at && now()->gt($booking->expires_at)) {
            $booking->update(['status' => 'cancelled']);
            return redirect()->route('show.details', $booking->show->slug)
                ->with('error', 'Your booking expired. Please select seats again.');
        }

        return view('payments.checkout', compact('booking'));
    }

    // -------------------------------------------------------------------------
    // Post-payment landing pages
    // -------------------------------------------------------------------------

    /**
     * PayPal redirects here on successful payment.
     * The actual capture + seats.io confirmation is handled by
     * GeneralAdmissionController::paypalSuccess() for GA shows, or by
     * BookingController::confirmBooking() called from your PayPal capture logic.
     *
     * Called by: GET /payment/success/{booking}
     */
    public function success(Booking $booking)
    {
        abort_unless(
            $booking->user_id === Auth::id() || $booking->customer_email === Auth::user()->email,
            403
        );

        $booking->load(['show', 'bookingItems.ticketType', 'tickets']);

        return view('payments.success', compact('booking'));
    }

    /**
     * PayPal redirects here when the user cancels payment.
     * Called by: GET /payment/cancel/{booking}
     */
    public function cancel(Booking $booking)
    {
        abort_unless(
            $booking->user_id === Auth::id() || $booking->customer_email === Auth::user()->email,
            403
        );

        // Only cancel if still pending — don't overwrite a confirmed booking
        if ($booking->status === 'pending') {
            $booking->update(['status' => 'cancelled', 'payment_status' => 'cancelled']);
            Log::info('Booking cancelled by user at PayPal cancel URL', [
                'booking_number' => $booking->booking_number,
                'user_id'        => Auth::id(),
            ]);
        }

        return redirect()->route('show.details', $booking->show->slug)
            ->with('warning', 'Payment was cancelled. Your seats have been released.');
    }

    /**
     * Shown when payment fails (e.g. PayPal returns an error).
     * Called by: GET /payment/failed/{booking}
     */
    public function failed(Booking $booking)
    {
        abort_unless(
            $booking->user_id === Auth::id() || $booking->customer_email === Auth::user()->email,
            403
        );

        $booking->load('show');

        return view('payments.failed', compact('booking'));
    }

    // -------------------------------------------------------------------------
    // User booking management
    // -------------------------------------------------------------------------

    /**
     * List all bookings for the authenticated user.
     * Called by: GET /my-bookings
     */
    public function myBookings(Request $request)
    {
        $bookings = Booking::with(['show:id,title,slug,start_date,featured_image'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('bookings.my', compact('bookings'));
    }

    /**
     * Show a single booking detail page.
     * Called by: GET /bookings/{booking}
     */
    public function showBooking(Booking $booking)
    {
        abort_unless(
            $booking->user_id === Auth::id() || $booking->customer_email === Auth::user()->email,
            403
        );

        $booking->load(['show', 'show.venue', 'bookingItems.ticketType', 'tickets']);

        return view('bookings.show', compact('booking'));
    }

    /**
     * Cancel a user's own booking.
     * Called by: POST /bookings/{booking}/cancel
     */
    public function cancelBooking(Request $request, Booking $booking)
    {
        abort_unless(
            $booking->user_id === Auth::id() || $booking->customer_email === Auth::user()->email,
            403
        );

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This booking cannot be cancelled.');
        }

        // Release seats in seats.io if this is a reserved show
        try {
            app(\App\Services\SeatsioService::class)->releaseBookingSeats($booking);
        } catch (\Throwable $e) {
            Log::warning('seats.io seat release failed on user cancel', [
                'booking_number' => $booking->booking_number,
                'error'          => $e->getMessage(),
            ]);
        }

        $booking->update([
            'status'         => 'cancelled',
            'payment_status' => 'refund_pending',
        ]);

        return redirect()->route('bookings.my')
            ->with('success', 'Your booking has been cancelled. Refunds are processed within 5–7 business days.');
    }
}
