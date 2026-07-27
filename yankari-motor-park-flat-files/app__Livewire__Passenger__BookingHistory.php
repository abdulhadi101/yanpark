<?php
// File path: app/Livewire/Passenger/BookingHistory.php

namespace App\Livewire\Passenger;

use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Support\Collection;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class BookingHistory extends Component
{
    public function getBookingsProperty(): Collection
    {
        return Booking::query()
            ->with(['trip.route', 'trip.vehicle', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function cancel(int $bookingId, BookingService $bookings): void
    {
        $booking = Booking::where('user_id', auth()->id())->findOrFail($bookingId);

        try {
            $bookings->cancel($booking);
            session()->flash('status', 'Booking cancelled.');
        } catch (\RuntimeException $e) {
            $this->addError('history', $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.passenger.booking-history');
    }
}
