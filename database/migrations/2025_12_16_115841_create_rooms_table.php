<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('rooms', function (Blueprint $table) {
        $table->id();
        $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
        $table->string('room_name');
        $table->decimal('price_per_night', 10, 2);
        $table->integer('max_guests');
        $table->boolean('ac')->default(false);
        $table->boolean('tv')->default(false);
        $table->boolean('breakfast')->default(false);
        $table->boolean('attached_bathroom')->default(false);
        $table->enum('availability', ['available', 'not_available'])->default('available');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
