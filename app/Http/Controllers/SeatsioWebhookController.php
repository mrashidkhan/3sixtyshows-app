<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * SeatsioWebhookController
 *
 * Receives incoming webhook events from seats.io.
 *
 * SETUP in seats.io Dashboard:
 *   1. Go to Manage → Webhooks
 *   2. Add endpoint: https://yourdomain.com/webhooks/seatsio
 *   3. Copy the signing secret → add to .env as SEATSIO_WEBHOOK_SECRET
 *   4. Add this route OUTSIDE the csrf middleware group in routes/web.php:
 *      Route::post('/webhooks/seatsio', [SeatsioWebhookController::class, 'handle'])
 *           ->name('webhooks.seatsio');
 *   5. Add the route to the CSRF exception list in app/Http/Middleware/VerifyCsrfToken.php:
 *      protected $except = ['webhooks/seatsio'];
 *
 * EVENTS WE HANDLE:
 *   - object.booked         → verify booking in DB
 *   - object.released       → log (seats already released by BookingController)
 *   - order.confirmed       → optional extra confirmation step
 */
class SeatsioWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // -------------------------------------------------------------------
        // 1. Signature verification (HMAC-SHA256)
        // -------------------------------------------------------------------
        $secret = config('services.seatsio.webhook_secret');

        if ($secret) {
            $signature = $request->header('X-Signature');
            $expected  = hash_hmac('sha256', $request->getContent(), $secret);

            if (!hash_equals($expected, $signature ?? '')) {
                Log::warning('seats.io webhook: invalid signature');
                return response()->json(['error' => 'Invalid signature'], 401);
            }
        }

        // -------------------------------------------------------------------
        // 2. Parse payload
        // -------------------------------------------------------------------
        $payload   = $request->json()->all();
        $eventType = $payload['type'] ?? 'unknown';

        // -------------------------------------------------------------------
        // 3. Log to webhook_logs using only existing fillable fields
        // -------------------------------------------------------------------
        $log = WebhookLog::create([
            'event_type' => $eventType,
            'payload'    => json_encode($payload),
            'status'     => 'received',
        ]);

        // -------------------------------------------------------------------
        // 4. Dispatch to handler
        // -------------------------------------------------------------------
        try {
            match (true) {
                str_starts_with($eventType, 'object.booked')   => $this->handleBooked($payload, $log),
                str_starts_with($eventType, 'object.released') => $this->handleReleased($payload, $log),
                str_starts_with($eventType, 'order.confirmed') => $this->handleOrderConfirmed($payload, $log),
                default                                         => $log->update(['status' => 'ignored']),
            };
        } catch (\Throwable $e) {
            Log::error('seats.io webhook processing error', [
                'event_type' => $eventType,
                'error'      => $e->getMessage(),
            ]);
            // 'error' is a valid status per WebhookLog::getStatusLabelAttribute()
            $log->update(['status' => 'error']);
        }

        return response()->json(['received' => true], 200);
    }

    // -----------------------------------------------------------------------
    // Handlers
    // -----------------------------------------------------------------------

    private function handleBooked(array $payload, WebhookLog $log): void
    {
        $orderRef  = $payload['orderRef'] ?? null;
        $objectIds = collect($payload['objects'] ?? [])->pluck('label')->toArray();

        if ($orderRef) {
            $booking = Booking::where('booking_number', $orderRef)->first();
            if ($booking) {
                $log->update([
                    'booking_id' => $booking->id,
                    'status'     => 'processed',
                ]);
                Log::info('seats.io webhook: seats booked confirmed in DB', [
                    'booking_number' => $orderRef,
                    'objects'        => $objectIds,
                ]);
                return;
            }
        }

        $log->update(['status' => 'processed']);
        Log::info('seats.io webhook: object.booked', ['objects' => $objectIds]);
    }

    private function handleReleased(array $payload, WebhookLog $log): void
    {
        $objectIds = collect($payload['objects'] ?? [])->pluck('label')->toArray();
        $log->update(['status' => 'processed']);
        Log::info('seats.io webhook: seats released', ['objects' => $objectIds]);
    }

    private function handleOrderConfirmed(array $payload, WebhookLog $log): void
    {
        $orderRef = $payload['orderRef'] ?? null;

        if ($orderRef) {
            $booking = Booking::where('booking_number', $orderRef)->first();
            if ($booking) {
                $log->update([
                    'booking_id' => $booking->id,
                    'status'     => 'processed',
                ]);
                return;
            }
        }

        $log->update(['status' => 'processed']);
    }
}
