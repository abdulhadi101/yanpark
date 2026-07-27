<?php
// File path: app/Services/BookingService.php

namespace App\Services;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Book the next available seat on a trip for a registered passenger.
     *
     * @throws \RuntimeException if the trip is not open or fully booked.
     */
    public function book(Trip $trip, User $passenger): Booking
    {
        return $this->reserveSeat($trip, [
            'user_id' => $passenger->id,
        ]);
    }

    /**
     * Book a seat for a walk-in passenger at the park counter — no online
     * account required. Used by the Cashier module.
     *
     * @throws \RuntimeException if the trip is not open or fully booked.
     */
    public function bookWalkIn(Trip $trip, string $name, string $phone): Booking
    {
        return $this->reserveSeat($trip, [
            'walk_in_name' => $name,
            'walk_in_phone' => $phone,
        ]);
    }

    public function cancel(Booking $booking): Booking
    {
        return DB::transaction(function () use ($booking) {
            if ($booking->status === 'paid') {
                throw new \RuntimeException('A paid booking cannot be cancelled here — a refund/void must be handled at the park office.');
            }

            $booking->update(['status' => 'cancelled']);
            $booking->trip()->decrement('seats_booked');

            return $booking->fresh();
        });
    }

    private function reserveSeat(Trip $trip, array $passengerFields): Booking
    {
        return DB::transaction(function () use ($trip, $passengerFields) {
            // Lock the trip row so two bookings can't grab the same last seat.
            $trip = Trip::query()->whereKey($trip->id)->lockForUpdate()->firstOrFail();

            if ($trip->status !== 'loading') {
                throw new \RuntimeException('This trip is no longer open for booking.');
            }

            if ($trip->isFull()) {
                throw new \RuntimeException('This trip is fully booked.');
            }

            $seatNo = $trip->seats_booked + 1;

            $booking = Booking::create(array_merge([
                'trip_id' => $trip->id,
                'reference' => 'YMP-'.strtoupper(Str::random(8)),
                'seat_no' => $seatNo,
                'status' => 'pending',
            ], $passengerFields));

            $trip->increment('seats_booked');

            return $booking;
        });
    }
}
