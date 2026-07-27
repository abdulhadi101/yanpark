<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\TripRoute;
use App\Models\Vehicle;
use Illuminate\Support\Collection;

class TripService
{
    /**
     * Open a new trip for loading at a park.
     */
    public function createTrip(Vehicle $vehicle, TripRoute $route, \DateTimeInterface $departureTime): Trip
    {
        return Trip::create([
            'vehicle_id' => $vehicle->id,
            'trip_route_id' => $route->id,
            'departure_time' => $departureTime,
            'status' => 'loading',
            'seats_booked' => 0,
        ]);
    }

    /**
     * Move a trip from "loading" to "dispatched" â€” the vehicle has left the park.
     */
    public function dispatch(Trip $trip): Trip
    {
        if ($trip->status !== 'loading') {
            throw new \RuntimeException('Only a trip that is still loading can be dispatched.');
        }

        $trip->update(['status' => 'dispatched']);

        return $trip->fresh();
    }

    public function complete(Trip $trip): Trip
    {
        $trip->update(['status' => 'completed']);

        return $trip->fresh();
    }

    public function cancel(Trip $trip): Trip
    {
        $trip->update(['status' => 'cancelled']);

        return $trip->fresh();
    }

    /**
     * Trips currently open for booking/dispatch at a given park.
     */
    public function loadingTripsForPark(int $parkId): Collection
    {
        return Trip::query()
            ->with(['vehicle', 'route'])
            ->whereHas('vehicle', fn ($q) => $q->where('park_id', $parkId))
            ->where('status', 'loading')
            ->orderBy('departure_time')
            ->get();
    }
}
