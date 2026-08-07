<?php

namespace App\Livewire\Passenger;

use App\Models\Booking;
use App\Models\Trip;
use App\Models\TripRoute;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class BookingFlow extends Component
{
    public string $originFilter = '';

    public string $travelDate;

    public ?int $selectedTripId = null;

    public ?Booking $activeBooking = null;

    public string $step = 'search'; // search -> confirm -> paid

    public function mount(): void
    {
        $this->travelDate = now()->toDateString();
    }

    public function getRoutesProperty(): Collection
    {
        return TripRoute::with('park')->orderBy('origin')->get();
    }

    public function getAvailableTripsProperty(): Collection
    {
        return Trip::query()
            ->with(['route.park', 'vehicle'])
            ->where('status', 'loading')
            ->whereDate('departure_time', $this->travelDate)
            ->when($this->originFilter !== '', function ($query) {
                $query->whereHas('route', fn ($q) => $q
                    ->where('origin', 'like', "%{$this->originFilter}%")
                    ->orWhere('destination', 'like', "%{$this->originFilter}%"));
            })
            ->orderBy('departure_time')
            ->get();
    }

    public function selectTrip(int $tripId): void
    {
        $this->selectedTripId = $tripId;
        $this->step = 'confirm';
    }

    public function confirmBooking(BookingService $bookings): void
    {
        $trip = Trip::findOrFail($this->selectedTripId);

        try {
            $this->activeBooking = $bookings->book($trip, auth()->user());
            $this->step = 'pay';
        } catch (\RuntimeException $e) {
            $this->addError('booking', $e->getMessage());
        }
    }

    public function pay(PaymentService $payments): void
    {
        if (! $this->activeBooking) {
            return;
        }

        $payments->pay($this->activeBooking->fresh());
        $this->step = 'paid';
    }

    public function backToSearch(): void
    {
        $this->reset(['selectedTripId', 'activeBooking', 'step']);
        $this->step = 'search';
    }

    public function getSelectedTripProperty(): ?Trip
    {
        if (! $this->selectedTripId) {
            return null;
        }

        return Trip::with(['route', 'vehicle'])->find($this->selectedTripId);
    }

    public function render()
    {
        return view('livewire.passenger.booking-flow');
    }
}
