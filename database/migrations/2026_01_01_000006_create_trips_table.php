<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            $table->foreignId('trip_route_id')->constrained()->cascadeOnDelete();
            $table->dateTime('departure_time');
            // loading    -> open for booking
            // dispatched -> vehicle has left the park
            // completed  -> trip finished
            // cancelled  -> trip called off
            $table->string('status')->default('loading');
            $table->unsignedTinyInteger('seats_booked')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
