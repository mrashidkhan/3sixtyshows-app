<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 1: Add seats.io fields to the shows table.
 *
 * Your existing shows table already has:
 *   - seating_type  enum('assigned','general_admission','mixed')
 *   - requires_seat_selection  tinyint
 *
 * seats.io works alongside those fields. We add:
 *   - ticketing_mode   — drives which booking flow to use
 *   - seatsio_chart_key  — the chart key from seats.io Designer
 *   - seatsio_event_key  — created via seats.io API when you publish an event
 *   - seatsio_public_key — workspace public key (can also live in .env, but
 *                          per-show override is handy for multi-workspace setups)
 *   - tickets_on_sale   — simple on/off gate
 *   - sale_starts_at    — nullable future date for pre-sale scheduling
 *
 * NOTE: We do NOT drop seating_type — keep it for backward-compat with your
 * existing GA bookings. The new ticketing_mode column drives the seats.io flow.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shows', function (Blueprint $table) {

            // ------------------------------------------------------------------
            // seats.io ticketing mode
            //   'none'             → no ticketing (external link / redirect shows)
            //   'general_admission'→ your current flow (TicketType + BookingItem)
            //   'reserved'         → full seats.io reserved seating
            //   'mixed'            → GA zones + reserved sections in same chart
            // ------------------------------------------------------------------
            $table->string('ticketing_mode', 30)
                  ->default('general_admission')
                  ->after('requires_seat_selection')
                  ->comment('none | general_admission | reserved | mixed');

            // ------------------------------------------------------------------
            // seats.io chart / event keys
            // ------------------------------------------------------------------
            $table->string('seatsio_chart_key')->nullable()
                  ->after('ticketing_mode')
                  ->comment('Chart key from seats.io Designer');

            $table->string('seatsio_event_key')->nullable()
                  ->after('seatsio_chart_key')
                  ->comment('Event key created via seats.io API on publish');

            $table->string('seatsio_public_key')->nullable()
                  ->after('seatsio_event_key')
                  ->comment('Workspace public key (overrides .env SEATSIO_PUBLIC_KEY)');

            // ------------------------------------------------------------------
            // Sale scheduling
            // ------------------------------------------------------------------
            $table->boolean('tickets_on_sale')
                  ->default(false)
                  ->after('seatsio_public_key');

            $table->timestamp('sale_starts_at')
                  ->nullable()
                  ->after('tickets_on_sale')
                  ->comment('Null = on sale immediately when tickets_on_sale = true');
        });
    }

    public function down(): void
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropColumn([
                'ticketing_mode',
                'seatsio_chart_key',
                'seatsio_event_key',
                'seatsio_public_key',
                'tickets_on_sale',
                'sale_starts_at',
            ]);
        });
    }
};
