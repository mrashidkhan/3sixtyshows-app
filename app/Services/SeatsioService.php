<?php

namespace App\Services;

use App\Models\Show;
use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\SeatsioChartSnapshot;
use Seatsio\SeatsioClient;
use Seatsio\Region;
use Illuminate\Support\Facades\Log;

/**
 * SeatsioService
 *
 * All seats.io API calls go through this class so that:
 *  1. The secret key is NEVER exposed to the browser — only used here
 *  2. Every operation is logged and easy to debug
 *  3. You can swap or mock the client in tests
 *
 * INSTALL FIRST:
 *   composer require seatsio/seatsio-php
 *
 * ADD TO config/services.php:
 *   'seatsio' => [
 *       'secret_key'     => env('SEATSIO_SECRET_KEY'),
 *       'public_key'     => env('SEATSIO_PUBLIC_KEY'),
 *       'region'         => env('SEATSIO_REGION', 'na'),
 *       'webhook_secret' => env('SEATSIO_WEBHOOK_SECRET'),
 *   ],
 */
class SeatsioService
{
    private SeatsioClient $client;

    public function __construct()
    {
        $region = config('services.seatsio.region', 'na') === 'eu'
    ? Region::EU()
    : Region::NA();

$this->client = new SeatsioClient(
    $region,
    config('services.seatsio.secret_key')
);
    }

    // =========================================================================
    // EVENT MANAGEMENT (Admin operations)
    // =========================================================================

    /**
     * Create a seats.io event linked to a chart.
     * Called from admin when setting up a show for reserved seating.
     * The returned event key is saved in shows.seatsio_event_key.
     *
     * @throws \Exception
     */
    public function createEvent(Show $show): string
    {
        $event = $this->client->events->create($show->seatsio_chart_key);
        Log::info('seats.io event created', [
            'show_id'       => $show->id,
            'event_key'     => $event->key,
            'chart_key'     => $show->seatsio_chart_key,
        ]);
        return $event->key;
    }

    /**
     * Create a seats.io event AND persist the key into the show record
     * in a single admin action.
     */
    public function createEventForShow(Show $show): Show
    {
        $eventKey = $this->createEvent($show);
        $show->update(['seatsio_event_key' => $eventKey]);
        $this->snapshotChart($show, auth()->id());
        return $show->fresh();
    }

    /**
     * Delete the seats.io event when a show is cancelled or deleted.
     * Safe to call even if the event doesn't exist (logs warning, does not throw).
     */
    public function deleteEvent(Show $show): void
    {
        if (!$show->seatsio_event_key) return;
        try {
            $this->client->events->delete($show->seatsio_event_key);
            Log::info('seats.io event deleted', ['event_key' => $show->seatsio_event_key]);
        } catch (\Throwable $e) {
            Log::warning('seats.io deleteEvent failed', [
                'event_key' => $show->seatsio_event_key,
                'error'     => $e->getMessage(),
            ]);
        }
    }

    /**
     * Retrieve chart metadata and store a snapshot in seatsio_chart_snapshots.
     * Called automatically when an event key is first saved to a show.
     */
    public function snapshotChart(Show $show, ?int $publishedBy = null): void
    {
        if (!$show->seatsio_chart_key) return;

        try {
            $chart = $this->client->charts->retrieve($show->seatsio_chart_key);

            // Build a simplified categories array from chart categories
            $categories = [];
            if (isset($chart->categories->list)) {
                foreach ($chart->categories->list as $cat) {
                    $categories[] = [
                        'key'      => $cat->key,
                        'label'    => $cat->label,
                        'color'    => $cat->color ?? null,
                        'accessible' => $cat->accessible ?? false,
                    ];
                }
            }

            SeatsioChartSnapshot::create([
                'show_id'              => $show->id,
                'seatsio_chart_key'    => $show->seatsio_chart_key,
                'seatsio_event_key'    => $show->seatsio_event_key,
                'chart_version'        => $chart->draftVersionNumber ?? null,
                'categories_snapshot'  => json_encode($categories),
                'total_capacity'       => null, // populated below if available
                'published_by'         => $publishedBy,
                'published_at'         => now(),
            ]);

            Log::info('seats.io chart snapshot saved', ['show_id' => $show->id]);
        } catch (\Throwable $e) {
            Log::warning('seats.io snapshotChart failed', [
                'show_id' => $show->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    // =========================================================================
    // HOLD TOKEN MANAGEMENT (Booking flow)
    // =========================================================================

    /**
     * Generate a fresh hold token for the seats.io JS widget.
     * This token is passed to the widget so it can hold selected seats.
     * The token must also be stored server-side and sent with the booking.
     *
     * @return string hold token UUID
     * @throws \Exception
     */
    // public function createHoldToken(Show $show, int $expiresInMinutes = 15): string
    // {
    //     $holdToken = $this->client->holdTokens->create($expiresInMinutes);
    //     Log::info('seats.io hold token created', [
    //         'show_id'    => $show->id,
    //         'token'      => $holdToken->holdToken,
    //         'expires_at' => $holdToken->expiresAt,
    //     ]);
    //     return $holdToken->holdToken;
    // }

    public function createHoldToken(Show $show, int $expiresInMinutes = 15): string
{
    $url = 'https://api-' . config('services.seatsio.region', 'na') . '.seatsio.net/hold-tokens';

    $response = \Illuminate\Support\Facades\Http::withBasicAuth(
            config('services.seatsio.secret_key'), ''
        )
        ->withHeaders(['Content-Length' => '0'])
        ->post($url, ['expiresInMinutes' => $expiresInMinutes]);

    if (!$response->successful()) {
        throw new \Exception('seats.io hold token creation failed: ' . $response->body());
    }

    $token = $response->json('holdToken');

    Log::info('seats.io hold token created', [
        'show_id'    => $show->id,
        'token'      => $token,
        'expires_at' => $response->json('expiresAt'),
    ]);

    return $token;
}

    /**
     * Refresh/extend an existing hold token expiry.
     * Call this when the user is still on the checkout page but the
     * timer is about to expire.
     */
    public function refreshHoldToken(string $holdToken, int $expiresInMinutes = 15): void
    {
        $this->client->holdTokens->expireInMinutes($holdToken, $expiresInMinutes);
        Log::info('seats.io hold token refreshed', ['token' => $holdToken]);
    }

    // =========================================================================
    // BOOKING CONFIRMATION
    // =========================================================================

    /**
     * Book (confirm) the selected seats after successful payment.
     * Called from BookingController::confirmPayment() AFTER the PayPal
     * webhook/capture has succeeded.
     *
     * @param  Show    $show
     * @param  array   $objectIds   e.g. ['A-1', 'A-2', 'B-5']
     * @param  string  $holdToken   the token used during seat selection
     * @param  string  $orderRef    your booking number e.g. 'BK-XXXX'
     * @return object  seats.io booking result
     * @throws \Exception
     */
    public function bookSeats(
        Show $show,
        array $objectIds,
        string $holdToken,
        string $orderRef
    ): object {
        $result = $this->client->events->book(
            $show->seatsio_event_key,
            $objectIds,
            $holdToken,
            $orderRef
        );

        Log::info('seats.io seats booked', [
            'show_id'    => $show->id,
            'event_key'  => $show->seatsio_event_key,
            'object_ids' => $objectIds,
            'order_ref'  => $orderRef,
        ]);

        return $result;
    }

    // =========================================================================
    // SEAT RELEASE / CANCELLATION
    // =========================================================================

    /**
     * Release seats back to available state.
     * Call this when a booking is cancelled or a payment fails.
     *
     * @param  Show   $show
     * @param  array  $objectIds
     * @param  string $orderRef   your booking number
     */
    public function releaseSeats(Show $show, array $objectIds, string $orderRef): void
    {
        if (!$show->seatsio_event_key || empty($objectIds)) return;
        try {
            $this->client->events->release(
                $show->seatsio_event_key,
                $objectIds,
                null, // no hold token needed for release
                $orderRef
            );
            Log::info('seats.io seats released', [
                'show_id'    => $show->id,
                'object_ids' => $objectIds,
                'order_ref'  => $orderRef,
            ]);
        } catch (\Throwable $e) {
            // Log and move on — do not block the cancellation flow
            Log::error('seats.io releaseSeats failed', [
                'show_id'    => $show->id,
                'object_ids' => $objectIds,
                'error'      => $e->getMessage(),
            ]);
        }
    }

    /**
     * Release seats for a booking by reading the booking items from your DB.
     * Convenience wrapper called from cancellation flows.
     */
    public function releaseBookingSeats(Booking $booking): void
    {
        $show = $booking->show;
        if (!$show || !$show->usesSeatsIo()) return;

        $objectIds = $booking->bookingItems()
            ->whereNotNull('seatsio_object_id')
            ->pluck('seatsio_object_id')
            ->toArray();

        $this->releaseSeats($show, $objectIds, $booking->booking_number);
    }

    // =========================================================================
    // STATUS QUERIES
    // =========================================================================

    /**
     * Get the status of a specific seat from seats.io.
     * Useful for admin or debugging — avoid calling this in the booking flow
     * (use the widget's real-time status instead).
     */
    public function getSeatStatus(Show $show, string $objectId): string
    {
        $obj = $this->client->events->retrieveObjectInfo(
            $show->seatsio_event_key,
            $objectId
        );
        return $obj->status ?? 'unknown';
    }

    /**
     * Retrieve all booked seats for an event — useful for capacity reports.
     */
    public function getBookedSeats(Show $show): array
    {
        if (!$show->seatsio_event_key) return [];
        $booked = [];
        $page = $this->client->events->statusChanges($show->seatsio_event_key)
            ->all();
        foreach ($page as $change) {
            if ($change->objectLabel) {
                $booked[] = $change->objectLabel;
            }
        }
        return $booked;
    }
}
