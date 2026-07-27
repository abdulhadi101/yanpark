<?php

namespace App\Livewire\Admin;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Reports extends Component
{
    public int $totalUsers = 0;
    public int $totalVehicles = 0;
    public int $totalTrips = 0;
    public int $totalBookings = 0;
    public float $todayRevenue = 0;
    public float $totalRevenue = 0;
    public int $tripsToday = 0;
    public int $passengersToday = 0;

    /** @var array<int, array{date: string, total: float}> */
    public array $revenueChart = [];

    /** @var \Illuminate\Support\Collection<int, object{label: string, trips: int}> */
    public $popularRoutes;

    public string $periodStart = '';
    public string $periodEnd = '';
    public string $periodLabel = '';
    public float $periodRevenue = 0;
    public int $periodBookings = 0;
    public int $periodTrips = 0;

    public function boot(): void
    {
        $this->popularRoutes = collect();
    }

    public function mount(ReportService $reports): void
    {
        $this->periodStart = now()->startOfMonth()->toDateString();
        $this->periodEnd = now()->toDateString();

        $this->loadStats($reports);
        $this->loadPeriod($reports);
    }

    public function loadStats(ReportService $reports): void
    {
        $this->totalUsers = $reports->totalUsers();
        $this->totalVehicles = $reports->totalVehicles();
        $this->totalTrips = $reports->totalTrips();
        $this->totalBookings = $reports->totalBookings();
        $this->todayRevenue = $reports->todayRevenue();
        $this->totalRevenue = $reports->totalRevenue();
        $this->tripsToday = $reports->tripsToday();
        $this->passengersToday = $reports->passengersToday();
        $this->revenueChart = $reports->revenueLastDays(7);
        $this->popularRoutes = $reports->popularRoutes(5);
    }

    public function loadPeriod(ReportService $reports): void
    {
        $summary = $reports->periodSummary($this->periodStart, $this->periodEnd);

        $this->periodLabel = $this->periodStart . ' → ' . $this->periodEnd;
        $this->periodRevenue = $summary['revenue'];
        $this->periodBookings = $summary['bookings'];
        $this->periodTrips = $summary['trips'];
    }

    public function render()
    {
        return view('livewire.admin.reports');
    }
}
