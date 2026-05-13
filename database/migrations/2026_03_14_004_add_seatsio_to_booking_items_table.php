<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 4: Extend booking_items for reserved-seat details.
 *
 * Your existing booking_items has: booking_id, ticket_type_id, seat_id,
 * general_admission_area_id, quantity, unit_price, total_price,
 * seat_identifier, item_metadata.
 *
 * For seats.io reserved seating we need the granular seat coordinates that
 * seats.io returns per object. These are stored per booking item (one row
 * per seat for reserved, one row per ticket-type batch for GA).
 *
 * New columns:
 *   seatsio_object_id   — seats.io objectId (e.g. "A-1", "Section A-Row 3-1")
 *   seat_section        — section label returned by seats.io
 *   seat_row            — row label returned by seats.io
 *   seat_number_label   — seat number label returned by seats.io
 *   category_label      — category label (e.g. "VIP", "General")
 *   item_type           — 'reserved_seat' | 'general_admission' | 'ga_area'
 *
 * NOTE: seat_identifier already exists in your table and will continue to store
 * the human-readable combined label (e.g. "A-3"). The new columns store the
 * individual parts for display and filtering purposes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {

            // seats.io unique object identifier for this seat/area
            $table->string('seatsio_object_id')->nullable()
                  ->after('seat_identifier')
                  ->comment('seats.io objectId; e.g. A-1 or uuid for GA objects');

            // Granular seat location data (returned by seats.io API/widget)
            $table->string('seat_section', 100)->nullable()
                  ->after('seatsio_object_id');

            $table->string('seat_row', 50)->nullable()
                  ->after('seat_section');

            $table->string('seat_number_label', 50)->nullable()
                  ->after('seat_row')
                  ->comment('Seat number as a display label (may differ from seat_number)');

            $table->string('category_label', 100)->nullable()
                  ->after('seat_number_label')
                  ->comment('Category label from seats.io chart');

            // Item type discriminator (replaces ambiguous null checks)
            $table->enum('item_type', ['reserved_seat', 'general_admission', 'ga_area'])
                  ->default('general_admission')
                  ->after('category_label');

            // Index for admin/reporting queries
            $table->index(['seatsio_object_id'], 'booking_items_seatsio_object_id_index');
        });
    }

    public function down(): void
    {
        Schema::table('booking_items', function (Blueprint $table) {
            $table->dropIndex('booking_items_seatsio_object_id_index');
            $table->dropColumn([
                'seatsio_object_id',
                'seat_section',
                'seat_row',
                'seat_number_label',
                'category_label',
                'item_type',
            ]);
        });
    }
};
