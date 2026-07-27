{{-- File path: resources/views/livewire/cashier/counter.blade.php --}}
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Park counter</h1>
        <p class="text-slate-500 text-sm">Verify payments, book walk-in passengers, and view today's transactions.</p>
    </div>

    @if (session('status'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @error('counter')
        <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
            {{ $message }}
        </div>
    @enderror

    <div class="bg-white rounded-xl border border-slate-200 p-4">
        <label class="block text-sm font-medium text-slate-600 mb-1">Park</label>
        <select wire:model.live="parkId" class="w-full max-w-sm rounded-lg border-slate-300">
            @foreach ($this->parks as $park)
                <option value="{{ $park->id }}">{{ $park->name }} — {{ $park->location }}</option>
            @endforeach
        </select>
    </div>

    {{-- Verify payment / confirm booking --}}
    <div class="space-y-3">
        <h2 class="font-medium text-slate-700">Pending online bookings — verify payment</h2>

        @forelse ($this->pendingBookings as $booking)
            <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-slate-800">{{ $booking->passengerName() }}</p>
                    <p class="text-sm text-slate-500">
                        {{ $booking->trip->route->label() }} · Seat #{{ $booking->seat_no }} ·
                        ₦{{ number_format($booking->trip->route->fare, 2) }} · Ref: {{ $booking->reference }}
                    </p>
                </div>
                <button wire:click="confirmAndCollectCash({{ $booking->id }})"
                    class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                    Confirm · collect cash
                </button>
            </div>
        @empty
            <div class="text-center text-slate-400 py-6 border border-dashed border-slate-200 rounded-xl text-sm">
                No pending bookings awaiting payment.
            </div>
        @endforelse
    </div>

    {{-- Walk-in booking --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <h2 class="font-medium text-slate-700">New walk-in booking</h2>

        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Trip</label>
                <select wire:model="walkInTripId" class="w-full rounded-lg border-slate-300">
                    <option value="">Select trip</option>
                    @foreach ($this->loadingTrips as $trip)
                        <option value="{{ $trip->id }}">
                            {{ $trip->route->label() }} · {{ $trip->departure_time->format('g:i A') }}
                            ({{ $trip->seatsAvailable() }} left)
                        </option>
                    @endforeach
                </select>
                @error('walkInTripId') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Passenger name</label>
                <input type="text" wire:model="walkInName" class="w-full rounded-lg border-slate-300">
                @error('walkInName') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Phone number</label>
                <input type="text" wire:model="walkInPhone" class="w-full rounded-lg border-slate-300">
                @error('walkInPhone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <button wire:click="recordWalkIn" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
            Book seat &amp; collect cash
        </button>

        @if ($lastBooking)
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-sm text-emerald-700 flex items-center justify-between">
                <span>Booked seat #{{ $lastBooking->seat_no }} for {{ $lastBooking->passengerName() }}.</span>
                <a href="{{ route('tickets.show', $lastBooking) }}" target="_blank" class="underline font-medium">Print ticket</a>
            </div>
        @endif
    </div>

    {{-- Today's transactions --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-medium text-slate-700 mb-3">Today's transactions</h2>
        <div class="divide-y divide-slate-100">
            @forelse ($this->todaysTransactions as $payment)
                <div class="py-2 flex items-center justify-between text-sm">
                    <div>
                        <p class="text-slate-700">{{ $payment->booking->passengerName() }} · {{ $payment->booking->trip->route->label() }}</p>
                        <p class="text-slate-400">{{ $payment->created_at->format('g:i A') }} · {{ ucfirst($payment->method) }} · Ref: {{ $payment->reference }}</p>
                    </div>
                    <span class="font-medium text-emerald-700">₦{{ number_format($payment->amount, 2) }}</span>
                </div>
            @empty
                <p class="text-slate-400 text-sm py-4 text-center">No transactions recorded yet today.</p>
            @endforelse
        </div>
    </div>
</div>
