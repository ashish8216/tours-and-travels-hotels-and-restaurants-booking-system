<?php

// database/migrations/xxxx_xx_xx_create_tour_dates_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_dates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('start_time')->nullable(); // e.g., 09:00 AM
            $table->integer('available_slots');
            $table->integer('booked_slots')->default(0);
            $table->enum('status', ['available', 'full', 'cancelled'])->default('available');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Prevent duplicate dates for same tour
            $table->unique(['tour_id', 'date', 'start_time']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_dates');
    }
};
