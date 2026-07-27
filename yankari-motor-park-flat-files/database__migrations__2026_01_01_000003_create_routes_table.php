<?php
// File path: database/migrations/2026_01_01_000003_create_routes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Named "trip_routes" (not "routes") to avoid clashing with Laravel's own
        // routing vocabulary anywhere in the codebase.
        Schema::create('trip_routes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('park_id')->constrained()->cascadeOnDelete();
            $table->string('origin');
            $table->string('destination');
            $table->decimal('fare', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trip_routes');
    }
};
