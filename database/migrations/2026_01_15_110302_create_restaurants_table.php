<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Restaurants table
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('cuisine_type')->nullable();
            $table->string('location');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->time('opening_time')->default('09:00:00');
            $table->time('closing_time')->default('22:00:00');
            $table->integer('capacity')->default(50);
            $table->string('image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            $table->softDeletes();
        });

        // Restaurant Tables
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('table_number');
            $table->string('table_name')->nullable();
            $table->integer('capacity');
            $table->enum('type', ['indoor', 'outdoor', 'private_room', 'bar'])->default('indoor');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->timestamps();
        });

        // Restaurant Reservations
        Schema::create('restaurant_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_table_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();
            $table->integer('number_of_people');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->enum('status', ['pending', 'confirmed', 'seated', 'completed', 'cancelled'])->default('pending');
            $table->text('special_requests')->nullable();
            $table->text('cancellation_reason')->nullable();
            $table->text('agent_notes')->nullable();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('seated_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->string('reservation_number')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_reservations');
        Schema::dropIfExists('restaurant_tables');
        Schema::dropIfExists('restaurants');
    }
};
