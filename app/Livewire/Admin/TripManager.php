<?php

namespace App\Livewire\Admin;

use App\Models\Park;
use App\Models\Trip;
use App\Models\TripRoute;
use App\Models\Vehicle;
use App\Services\TripService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TripManager extends Component
{
    public ?int $parkId = null;

    public ?int $vehicleId = null;

    public ?int $routeId = null;

    public string $departureTime = '';

    public function mount(): void
    {
        $this->parkId = Park::query()->value('id');
        $this->departureTime = now()->addHour()->format('Y-m-d\TH:i');
    }

    public function getParksProperty(): Collection
    {
        return Park::orderBy('name')->get();
    }

    public function getVehiclesProperty(): Collection
    {
        if (! $this->parkId) {
            return collect();
        }

        return Vehicle::where('park_id', $this->parkId)->orderBy('plate_no')->get();
    }

    public function getRoutesProperty(): Collection
    {
        if (! $this->parkId) {
            return collect();
        }

        return TripRoute::where('park_id', $this->parkId)->orderBy('origin')->get();
    }

    /**
     * All non-completed/cancelled trips for the selected park â€” covers both
     * "loading" (open for booking) and "dispatched" (in transit) so admin
     * can track and complete them from one screen.
     */
    public function getActiveTripsProperty(): Collection
    {
        if (! $this->parkId) {
            return collect();
        }

        return Trip::query()
            ->with(['vehicle', 'route'])
            ->whereHas('vehicle', fn ($q) => $q->where('park_id', $this->parkId))
            ->whereIn('status', ['loading', 'dispatched'])
            ->orderBy('departure_time')
            ->get();
    }

    public function createTrip(TripService $trips): void
    {
        $this->validate([
            'vehicleId' => ['required', 'exists:vehicles,id'],
            'routeId' => ['required', 'exists:trip_routes,id'],
            'departureTime' => ['required', 'date'],
        ]);

        $trips->createTrip(
            Vehicle::findOrFail($this->vehicleId),
            TripRoute::findOrFail($this->routeId),
            \Illuminate\Support\Carbon::parse($this->departureTime)
        );

        $this->reset(['vehicleId', 'routeId']);
        session()->flash('status', 'Trip opened for loading.');
    }

    // Named dispatch_() (not dispatch()) because Livewire's base Component
    // class already defines dispatch() for firing browser events.
    public function dispatch_(int $tripId, TripService $trips): void
    {
        $trips->dispatch(Trip::findOrFail($tripId));
        session()->flash('status', 'Trip dispatched.');
    }

    public function complete(int $tripId, TripService $trips): void
    {
        $trips->complete(Trip::findOrFail($tripId));
        session()->flash('status', 'Trip marked completed.');
    }

    public function cancelTrip(int $tripId, TripService $trips): void
    {
        $trips->cancel(Trip::findOrFail($tripId));
        session()->flash('status', 'Trip cancelled.');
    }

    public function render()
    {
        return view('livewire.admin.trip-manager');
    }
}
