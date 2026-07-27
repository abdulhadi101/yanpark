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
            ->selectRaw('trip_routes.origin as origin, trip_routes.destination as destination, COUNT(*) as trips')
            ->groupBy('trip_routes.origin', 'trip_routes.destination')
            ->orderByDesc('trips')
            ->limit($limit)
            ->get()
            ->map(function ($row) {
                $row->label = "{$row->origin} → {$row->destination}";

                return $row;
            });
    }

    /**
     * Daily / weekly / monthly summary for the Reports page.
     *
     * @return array{label: string, start: string, end: string, revenue: float,
     *               bookings: int, trips: int, breakdown: array<int, array{date: string, revenue: float, bookings: int, trips: int}>}
     */
    public function periodSummary(string $period, ?Carbon $reference = null): array
    {
        $reference ??= Carbon::today();

        [$start, $end, $label] = match ($period) {
            'weekly' => [$reference->copy()->startOfWeek(), $reference->copy()->endOfWeek(), 'This week'],
            'monthly' => [$reference->copy()->startOfMonth(), $reference->copy()->endOfMonth(), 'This month'],
            default => [$reference->copy(), $reference->copy(), 'Today'],
        };

        $revenue = (float) Payment::query()
            ->where('status', 'successful')
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->sum('amount');

        $bookings = Booking::query()
            ->where('status', 'paid')
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->count();

        $trips = Trip::query()
            ->whereBetween('departure_time', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->count();

        $revenueByDay = Payment::query()
            ->selectRaw('DATE(created_at) as day, SUM(amount) as total')
            ->where('status', 'successful')
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('day')
            ->pluck('total', 'day');

        $bookingsByDay = Booking::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('status', 'paid')
            ->whereBetween('created_at', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('day')
            ->pluck('total', 'day');

        $tripsByDay = Trip::query()
            ->selectRaw('DATE(departure_time) as day, COUNT(*) as total')
            ->whereBetween('departure_time', [$start->copy()->startOfDay(), $end->copy()->endOfDay()])
            ->groupBy('day')
            ->pluck('total', 'day');

        $breakdown = [];
        $cursor = $start->copy();
        while ($cursor->lte($end)) {
            $day = $cursor->toDateString();
            $breakdown[] = [
                'date' => $day,
                'revenue' => (float) ($revenueByDay[$day] ?? 0),
                'bookings' => (int) ($bookingsByDay[$day] ?? 0),
                'trips' => (int) ($tripsByDay[$day] ?? 0),
            ];
            $cursor->addDay();
        }

        return [
            'label' => $label,
            'start' => $start->toDateString(),
            'end' => $end->toDateString(),
            'revenue' => $revenue,
            'bookings' => $bookings,
            'trips' => $trips,
            'breakdown' => $breakdown,
        ];
    }
}
}
