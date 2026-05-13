<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 5: Extend tickets for seats.io seat details.
 *
 * Your existing tickets table already has: show_id, user_id, booking_id,
 * ticket_type_id, seat_id, ticket_number, price, status, seat_number,
 * seat_identifier, ticket_mode, ticket_metadata, purchased_date, qr_code.
 *
 * For seats.io we need to record the exact seat object from seats.io so
 * that check-in and re-entry scanning can validate against seats.io's
 * API if needed.
 *
 * New columns:
 *   seatsio_object_id — seats.io objectId for this individual ticket's seat
 *   seat_section      — section label
 *   seat_row          — row label
 *   seat_label        — full label string returned by seats.io (e.g. "Section A - Row 3 - Seat 5")
 *   checked_in_at     — timestamp when ticket was scanned/used at the door
 *   checked_in_by     — user_id of the staff member who scanned it
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {

            // seats.io object identifier for this specific seat
            $table->string('seatsio_object_id')->nullable()
                  ->after('seat_identifier')
                  ->comment('seats.io objectId for check-in API validation');

            // Granular seat location (denormalised for fast display)
            $table->string('seat_section', 100)->nullable()->after('seatsio_object_id');
            $table->string('seat_row', 50)->nullable()->after('seat_section');

            // Full human-readable label from seats.io (e.g. "A-5" or "VIP Row 2 Seat 3")
            $table->string('seat_label')->nullable()
                  ->after('seat_row')
                  ->comment('Full label string returned by seats.io widget/API');

            // Door check-in tracking
            $table->timestamp('checked_in_at')->nullable()->after('qr_code');

            $table->unsignedBigInteger('checked_in_by')->nullable()
                  ->after('checked_in_at')
                  ->comment('user_id of staff member who performed check-in');

            $table->foreign('checked_in_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');

            // Index to speed up check-in lookups by seats.io object
            $table->index(['seatsio_object_id'], 'tickets_seatsio_object_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['checked_in_by']);
            $table->dropIndex('tickets_seatsio_object_id_index');
            $table->dropColumn([
                'seatsio_object_id',
                'seat_section',
                'seat_row',
                'seat_label',
                'checked_in_at',
                'checked_in_by',
            ]);
        });
    }
};
