<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 2: Extend ticket_types for seats.io category mapping.
 *
 * Your existing ticket_types table already has:
 *   id, show_id, name, description, type (enum), price, capacity,
 *   available_quantity, sold_quantity, is_active, display_order
 *
 * seats.io uses "categories" in the chart Designer. Each category has a key
 * (string) and a label. Your TicketType maps 1-to-1 to a seats.io category.
 *
 * New columns:
 *   seatsio_category_key  — matches the category key set in the Designer
 *   seatsio_section_key   — optional: restrict type to a specific section/zone
 *   booking_type          — 'general_admission' | 'reserved' (per ticket type)
 *   min_quantity          — minimum purchase quantity (default 1)
 *   max_quantity          — maximum per transaction (null = unlimited)
 *   sale_start_date       — ticket-level on-sale date (overrides show level)
 *   sale_end_date         — when this ticket type stops being sold
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {

            // seats.io Designer category key — must match exactly
            $table->string('seatsio_category_key')->nullable()
                  ->after('display_order')
                  ->comment('Category key in seats.io Designer (case-sensitive)');

            // Optional section filter (for mixed charts)
            $table->string('seatsio_section_key')->nullable()
                  ->after('seatsio_category_key')
                  ->comment('Restrict this ticket type to a specific section/zone');

            // Per-type booking mode (inherits from show if null)
            $table->enum('booking_type', ['general_admission', 'reserved'])
                  ->default('general_admission')
                  ->after('seatsio_section_key');

            // Quantity controls
            $table->unsignedTinyInteger('min_quantity')
                  ->default(1)
                  ->after('booking_type');

            $table->unsignedSmallInteger('max_quantity')
                  ->nullable()
                  ->after('min_quantity')
                  ->comment('Max tickets per transaction; null = no limit');

            // Per-ticket-type sale window
            $table->timestamp('sale_start_date')->nullable()->after('max_quantity');
            $table->timestamp('sale_end_date')->nullable()->after('sale_start_date');

            // Color swatch for the booking UI (hex, e.g. #FF6B6B)
            $table->string('color', 10)->nullable()->after('sale_end_date');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_types', function (Blueprint $table) {
            $table->dropColumn([
                'seatsio_category_key',
                'seatsio_section_key',
                'booking_type',
                'min_quantity',
                'max_quantity',
                'sale_start_date',
                'sale_end_date',
                'color',
            ]);
        });
    }
};
