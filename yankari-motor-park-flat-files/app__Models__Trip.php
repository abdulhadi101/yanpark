<?php
// File path: app/Models/Trip.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['vehicle_id', 'trip_route_id', 'departure_time', 'status', 'seats_booked'];

    protected function casts(): array
    {
        return ['departure_time' => 'datetime'];
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function route(): BelongsTo
    {
        return $this->belongsTo(TripRoute::class, 'trip_route_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function seatsAvailable(): int
    {
        return max(0, $this->vehicle->capacity - $this->seats_booked);
    }

    public function isFull(): bool
    {
        return $this->seatsAvailable() <= 0;
    }
}
