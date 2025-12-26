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
        Schema::table('agent_requests', function (Blueprint $table) {
            // Change from enum to string
            $table->string('business_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('agent_requests', function (Blueprint $table) {
            // Change back to enum if needed
            $table->enum('business_type', ['hotel', 'restaurant'])->change();
        });
    }
};
