<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('room_bookings', function (Blueprint $table) {
            $table->id();

            // Ownership
            $table->foreignId('agent_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('room_id')
                ->constrained()
                ->cascadeOnDelete();

            // Frontend booking (user)
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Manual booking (walk-in / phone)
            $table->string('guest_name')->nullable();
            $table->string('guest_phone')->nullable();

            // Dates
            $table->date('check_in');
            $table->date('check_out');

            // Pricing
            $table->decimal('price_per_night', 10, 2);
            $table->decimal('total_amount', 10, 2);

            // Booking state
            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled',
                'checked_in',
                'checked_out'
            ])->default('pending');

            // Booking origin
            $table->enum('booking_source', [
                'frontend',
                'manual'
            ]);

            $table->timestamps();

            // Indexes (performance)
            $table->index(['agent_id', 'status']);
            $table->index(['room_id', 'check_in', 'check_out']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_bookings');
    }
};
