<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // A walk-in passenger booked by the cashier has no account, so
            // user_id becomes optional and we capture their name/phone instead.
            $table->foreignId('user_id')->nullable()->change();
            $table->string('walk_in_name')->nullable()->after('user_id');
            $table->string('walk_in_phone')->nullable()->after('walk_in_name');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['walk_in_name', 'walk_in_phone']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
