<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 8: Create seatsio_chart_snapshots table.
 *
 * When a seats.io chart is updated in the Designer, existing published events
 * may be affected. This table stores a snapshot of chart metadata (categories,
 * sections, total capacity) each time a chart is published to an event.
 *
 * This is optional but highly recommended for:
 *   1. Auditing — knowing what the chart looked like when each event was created
 *   2. Capacity reporting — quick DB query without hitting the seats.io API
 *   3. Rollback detection — alerting if chart changes break existing bookings
 *
 * One row per (show_id + seatsio_event_key + published_at).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seatsio_chart_snapshots', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('show_id');
            $table->string('seatsio_chart_key')
                  ->comment('Chart key from seats.io Designer');
            $table->string('seatsio_event_key')
                  ->comment('Event key in seats.io (linked to the show)');

            // Chart version / draft number from seats.io (optional, for audit trail)
            $table->unsignedInteger('chart_version')->nullable()
                  ->comment('Chart draft/version number at time of snapshot');

            // Snapshot of category structure (JSON array)
            // [{"key":"vip","label":"VIP","capacity":50,"color":"#FFD700"}, ...]
            $table->longText('categories_snapshot')
                  ->comment('JSON: array of category objects from seats.io at publish time');

            // Total bookable capacity derived from the chart
            $table->unsignedInteger('total_capacity')->nullable();

            // Who triggered the publish (admin user_id)
            $table->unsignedBigInteger('published_by')->nullable();

            $table->timestamp('published_at')
                  ->comment('When this event was published/created in seats.io');

            $table->timestamps();

            $table->index('show_id');
            $table->index('seatsio_event_key');

            $table->foreign('show_id')
                  ->references('id')->on('shows')
                  ->onDelete('cascade');

            $table->foreign('published_by')
                  ->references('id')->on('users')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seatsio_chart_snapshots');
    }
};
