<?php

// database/migrations/xxxx_xx_xx_create_tours_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->decimal('price', 10, 2);
            $table->integer('max_people')->default(10);
            $table->string('duration'); // e.g., "2 hours", "Full day", "3 days"
            $table->integer('duration_hours')->nullable(); // For sorting/filtering
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('image')->nullable();
            $table->json('images')->nullable(); // Multiple images
            $table->string('difficulty_level')->nullable(); // Easy, Moderate, Hard
            $table->text('inclusions')->nullable(); // What's included
            $table->text('exclusions')->nullable(); // What's not included
            $table->text('requirements')->nullable(); // What users need to bring
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
