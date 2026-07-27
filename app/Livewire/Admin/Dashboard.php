<?php

namespace App\Livewire\Admin;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Dashboard extends Component
{
    public function render(ReportService $reports)
    {
        return view('livewire.admin.dashboard', [
            'todayRevenue' => $reports->todayRevenue(),
            'totalRevenue' => $reports->totalRevenue(),
            'tripsToday' => $reports->tripsToday(),
            'passengersToday' => $reports->passengersToday(),
            'revenueLastDays' => $reports->revenueLastDays(7),
            'popularRoutes' => $reports->popularRoutes(5),
            'totalUsers' => $reports->totalUsers(),
            'totalVehicles' => $reports->totalVehicles(),
            'totalTrips' => $reports->totalTrips(),
            'totalBookings' => $reports->totalBookings(),
        ]);
    }
}
