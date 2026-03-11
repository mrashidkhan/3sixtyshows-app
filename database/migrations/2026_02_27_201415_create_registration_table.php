<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * php artisan migrate
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('city');
            $table->string('event');                         // e.g. bismil_ki_mehfil_houston
            $table->string('source')->nullable();            // facebook, instagram, friend, other
            $table->string('status')->default('pending');   // pending | confirmed | disqualified
            $table->timestamps();

            // Prevent duplicate entries (same email OR same phone per event)
            $table->unique(['email', 'event'], 'unique_email_per_event');
            $table->unique(['phone', 'event'], 'unique_phone_per_event');

            // Fast lookup indexes
            $table->index('event');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
