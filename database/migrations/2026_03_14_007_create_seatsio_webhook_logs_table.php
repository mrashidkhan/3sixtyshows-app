<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 7: Create seatsio_webhook_logs table.
 *
 * seats.io fires webhooks for events like:
 *   - event.created / event.updated
 *   - object.booked / object.released / object.held
 *   - order.confirmed / order.cancelled
 *
 * You already have a webhook_logs table (for general use), but it's better to
 * have a dedicated seats.io log for:
 *   1. Debugging seat state discrepancies between seats.io and your DB
 *   2. Replaying missed webhooks without polluting your general webhook log
 *   3. Attaching the seats.io event_key and object_id for fast lookups
 *
 * If you prefer to reuse your existing webhook_logs table, skip this migration
 * and add a 'source' column to webhook_logs instead.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seatsio_webhook_logs', function (Blueprint $table) {
            $table->id();

            // seats.io event this webhook is about
            $table->string('seatsio_event_key')->nullable()
                  ->comment('The seats.io event key this webhook relates to');

            // seats.io webhook type (e.g. "object.booked", "order.cancelled")
            $table->string('event_type', 100)
                  ->comment('seats.io webhook event type string');

            // Raw payload stored as JSON for replay / debugging
            $table->longText('payload')
                  ->comment('Raw JSON body of the seats.io webhook call');

            // Processing status
            $table->enum('status', ['received', 'processed', 'failed', 'ignored'])
                  ->default('received');

            // Error message if processing failed
            $table->text('error_message')->nullable();

            // Which show/booking this webhook was matched to (if any)
            $table->unsignedBigInteger('show_id')->nullable();
            $table->unsignedBigInteger('booking_id')->nullable();

            // seats.io object(s) referenced in the webhook (comma-separated for multi)
            $table->string('object_ids')->nullable()
                  ->comment('seats.io objectId(s) referenced; comma-separated for multi-seat webhooks');

            // HTTP metadata for debugging
            $table->string('source_ip', 45)->nullable();
            $table->unsignedSmallInteger('response_code')->nullable();

            $table->timestamps();

            // Indexes for admin filtering
            $table->index(['seatsio_event_key', 'event_type'], 'seatsio_wh_event_key_type_index');
            $table->index(['status', 'created_at'], 'seatsio_wh_status_created_index');
            $table->index('show_id');
            $table->index('booking_id');

            // Optional FK — use if you want cascade cleanup
            // $table->foreign('show_id')->references('id')->on('shows')->onDelete('set null');
            // $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seatsio_webhook_logs');
    }
};
