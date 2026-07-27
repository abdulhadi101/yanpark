<?php
// File path: app/Livewire/Cashier/Counter.php

namespace App\Livewire\Cashier;

use App\Models\Booking;
use App\Models\Park;
use App\Models\Payment;
use App\Models\Trip;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Counter extends Component
{
    public ?int $parkId = null;

    // Walk-in booking form fields
    public ?int $walkInTripId = null;

    public string $walkInName = '';

    public string $walkInPhone = '';

    public ?Booking $lastBooking = null;

    public function mount(): void
    {
        $this->parkId = Park::query()->value('id');
    }

    public function getParksProperty(): Collection
    {
        return Park::orderBy('name')->get();
    }

    /** Trips currently loading at the selected park — passengers can still be added. */
    public function getLoadingTripsProperty(): Collection
    {
        if (! $this->parkId) {
            return collect();
        }

        return Trip::query()
            ->with(['vehicle', 'route'])
            ->whereHas('vehicle', fn ($q) => $q->where('park_id', $this->parkId))
            ->where('status', 'loading')
            ->orderBy('departure_time')
            ->get();
    }

    /** Bookings made online (or earlier) that still need payment verified at the counter. */
    public function getPendingBookingsProperty(): Collection
    {
        if (! $this->parkId) {
            return collect();
        }

        return Booking::query()
            ->with(['trip.route', 'trip.vehicle', 'user'])
            ->where('status', 'pending')
            ->whereHas('trip.vehicle', fn ($q) => $q->where('park_id', $this->parkId))
            ->latest()
            ->get();
    }

    /** Today's successful payments at this park, for the "view transactions" function. */
    public function getTodaysTransactionsProperty(): Collection
    {
        if (! $this->parkId) {
            return collect();
        }

        return Payment::query()
            ->with(['booking.trip.route', 'booking.user'])
            ->whereDate('created_at', now()->toDateString())
            ->whereHas('booking.trip.vehicle', fn ($q) => $q->where('park_id', $this->parkId))
            ->latest()
            ->get();
    }

    /**
     * Verify payment + confirm a booking that was made online — collects cash
     * and marks it paid. Covers the report's "Verify Payment" / "Confirm
     * Booking" Cashier functions.
     */
    public function confirmAndCollectCash(int $bookingId, PaymentService $payments): void
    {
        $booking = Booking::findOrFail($bookingId);

        try {
            $payments->pay($booking, 'cash');
            session()->flash('status', 'Payment confirmed for '.$booking->passengerName().'.');
        } catch (\RuntimeException $e) {
            $this->addError('counter', $e->getMessage());
        }
    }

    /**
     * Register a walk-in passenger (no online account) and immediately
     * collect cash payment — the core "walk-in booking" flow at the park
     * counter.
     */
    public function recordWalkIn(BookingService $bookings, PaymentService $payments): void
    {
        $this->validate([
            'walkInTripId' => ['required', 'exists:trips,id'],
            'walkInName' => ['required', 'string', 'max:255'],
            'walkInPhone' => ['required', 'string', 'max:20'],
        ]);

        try {
            $trip = Trip::findOrFail($this->walkInTripId);
            $booking = $bookings->bookWalkIn($trip, $this->walkInName, $this->walkInPhone);
            $payments->pay($booking, 'cash');

            $this->lastBooking = $booking->fresh();
            $this->reset(['walkInTripId', 'walkInName', 'walkInPhone']);
            session()->flash('status', 'Walk-in booking recorded and paid.');
        } catch (\RuntimeException $e) {
            $this->addError('counter', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.cashier.counter');
    }
}
