<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\Park;
use App\Models\Payment;
use App\Models\Trip;
use App\Models\TripRoute;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Users (one per role, easy demo credentials) -------------------

        $admin = User::updateOrCreate(
            ['email' => 'admin@ymp.test'],
            ['name' => 'Park Admin', 'password' => Hash::make('password'), 'role' => 'admin', 'phone' => '08010000001'],
        );

        $cashier = User::updateOrCreate(
            ['email' => 'cashier@ymp.test'],
            ['name' => 'Park Cashier', 'password' => Hash::make('password'), 'role' => 'cashier', 'phone' => '08010000002'],
        );

        $passenger = User::updateOrCreate(
            ['email' => 'passenger@ymp.test'],
            ['name' => 'Demo Passenger', 'password' => Hash::make('password'), 'role' => 'passenger', 'phone' => '08010000003'],
        );

        // ---- Park, routes, drivers, vehicles --------------------------------

        $park = Park::updateOrCreate(
            ['name' => 'Yankari Motor Park'],
            ['location' => 'Bauchi, Bauchi State'],
        );

        $routes = collect([
            ['origin' => 'Bauchi', 'destination' => 'Jos', 'fare' => 3500],
            ['origin' => 'Bauchi', 'destination' => 'Kano', 'fare' => 4500],
            ['origin' => 'Bauchi', 'destination' => 'Abuja', 'fare' => 8000],
            ['origin' => 'Bauchi', 'destination' => 'Gombe', 'fare' => 2500],
        ])->map(fn ($r) => TripRoute::updateOrCreate(
            ['origin' => $r['origin'], 'destination' => $r['destination']],
            ['fare' => $r['fare'], 'park_id' => $park->id],
        ));

        $drivers = collect([
            ['name' => 'Musa Ibrahim', 'phone' => '08020000001', 'license_no' => 'BAU-DL-1001'],
            ['name' => 'Sani Yakubu', 'phone' => '08020000002', 'license_no' => 'BAU-DL-1002'],
            ['name' => 'Aliyu Bello', 'phone' => '08020000003', 'license_no' => 'BAU-DL-1003'],
        ])->map(fn ($d) => Driver::updateOrCreate(
            ['license_no' => $d['license_no']],
            ['name' => $d['name'], 'phone' => $d['phone']],
        ));

        $vehicles = collect([
            ['plate_no' => 'BA 123 XYZ', 'type' => 'bus', 'capacity' => 18, 'driver_id' => $drivers[0]->id],
            ['plate_no' => 'BA 456 XYZ', 'type' => 'coaster', 'capacity' => 14, 'driver_id' => $drivers[1]->id],
            ['plate_no' => 'BA 789 XYZ', 'type' => 'car', 'capacity' => 4, 'driver_id' => $drivers[2]->id],
        ])->map(fn ($v) => Vehicle::updateOrCreate(
            ['plate_no' => $v['plate_no']],
            ['type' => $v['type'], 'capacity' => $v['capacity'], 'driver_id' => $v['driver_id'], 'park_id' => $park->id],
        ));

        // ---- Trips: a mix of loading / dispatched / completed with revenue --

        // A completed trip yesterday, with 3 paid bookings (feeds revenue chart).
        $yesterdayTrip = Trip::firstOrCreate(
            ['vehicle_id' => $vehicles[0]->id, 'departure_time' => Carbon::yesterday()->setTime(9, 0)],
            ['trip_route_id' => $routes[0]->id, 'status' => 'completed', 'seats_booked' => 3],
        );

        foreach (range(1, 3) as $seat) {
            $booking = Booking::firstOrCreate(
                ['trip_id' => $yesterdayTrip->id, 'seat_no' => $seat],
                [
                    'user_id' => $passenger->id,
                    'reference' => 'YMP-'.strtoupper(Str::random(8)),
                    'status' => 'paid',
                ],
            );

            if ($booking->wasRecentlyCreated) {
                Payment::create([
                    'booking_id' => $booking->id,
                    'amount' => $routes[0]->fare,
                    'method' => 'mock_card',
                    'status' => 'successful',
                    'reference' => 'PAY-'.strtoupper(Str::random(10)),
                    'created_at' => Carbon::yesterday(),
                    'updated_at' => Carbon::yesterday(),
                ]);
            }
        }

        // A dispatched trip earlier today, one paid seat.
        $todayTrip = Trip::firstOrCreate(
            ['vehicle_id' => $vehicles[1]->id, 'departure_time' => Carbon::today()->setTime(7, 30)],
            ['trip_route_id' => $routes[1]->id, 'status' => 'dispatched', 'seats_booked' => 1],
        );

        $booking = Booking::firstOrCreate(
            ['trip_id' => $todayTrip->id, 'seat_no' => 1],
            [
                'user_id' => $passenger->id,
                'reference' => 'YMP-'.strtoupper(Str::random(8)),
                'status' => 'paid',
            ],
        );

        if ($booking->wasRecentlyCreated) {
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $routes[1]->fare,
                'method' => 'mock_card',
                'status' => 'successful',
                'reference' => 'PAY-'.strtoupper(Str::random(10)),
            ]);
        }

        // A trip still loading right now — ready for the admin demo to dispatch,
        // open for the passenger demo to book against, and used below for a
        // pending booking the cashier can confirm at the counter.
        $loadingTrip = Trip::firstOrCreate(
            ['vehicle_id' => $vehicles[2]->id, 'departure_time' => Carbon::now()->addHours(2)->startOfMinute()],
            ['trip_route_id' => $routes[2]->id, 'status' => 'loading', 'seats_booked' => 1],
        );

        // A booking made online but not yet paid — lets the cashier demo
        // "verify payment / confirm booking" against a real pending record.
        Booking::firstOrCreate(
            ['trip_id' => $loadingTrip->id, 'seat_no' => 1],
            [
                'user_id' => $passenger->id,
                'reference' => 'YMP-'.strtoupper(Str::random(8)),
                'status' => 'pending',
            ],
        );

        $this->command?->info('Demo data seeded. Logins (password for all: "password"):');
        $this->command?->info('  Admin:     admin@ymp.test');
        $this->command?->info('  Cashier:   cashier@ymp.test');
        $this->command?->info('  Passenger: passenger@ymp.test');
    }
}
