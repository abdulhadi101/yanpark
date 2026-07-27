<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Trip;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function totalUsers(): int
    {
        return User::count();
    }

    public function totalVehicles(): int
    {
        return Vehicle::count();
    }

    public function totalTrips(): int
    {
        return Trip::count();
    }

    public function totalBookings(): int
    {
        return Booking::count();
    }

    public function todayRevenue(): float
    {
        return (float) Payment::query()
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'successful')
            ->sum('amount');
    }

    public function totalRevenue(): float
    {
        return (float) Payment::query()->where('status', 'successful')->sum('amount');
    }

    public function tripsToday(): int
    {
        return Trip::query()->whereDate('departure_time', Carbon::today())->count();
    }

    public function passengersToday(): int
    {
        return Booking::query()
            ->whereDate('created_at', Carbon::today())
            ->where('status', 'paid')
            ->count();
    }

    /**
     * Revenue per day for the last N days, for a simple bar chart.
     *
     * @return array<int, array{date: string, total: float}>
     */
    public function revenueLastDays(int $days = 7): array
    {
        $rows = Payment::query()
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->where('status', 'successful')
            ->where('created_at', '>=', Carbon::today()->subDays($days - 1))
            ->groupBy('day')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $result[] = [
                'date' => $date,
                'total' => (float) ($rows[$date]->total ?? 0),
            ];
        }

        return $result;
    }

    /**
     * @return \Illuminate\Support\Collection<int, object{label: string, trips: int}>
     */
    public function popularRoutes(int $limit = 5)
    {
        return DB::table('trips')
            ->join('trip_routes', 'trips.trip_route_id', '=', 'trip_routes.id')
            ->selectRaw("CONCAT(trip_routes.origin, ' â†’ ', trip_routes.destination) as label, COUNT(*) as trips")
            ->groupBy('trip_routes.origin', 'trip_routes.destination')
            ->orderByDesc('trips')
            ->limit($limit)
            ->get();
    }
}
