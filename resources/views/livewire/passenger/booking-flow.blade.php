<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Book a trip</h1>
        <p class="text-slate-500 text-sm">Yankari Motor Park, Bauchi</p>
    </div>

    @error('booking')
        <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
            {{ $message }}
        </div>
    @enderror

    {{-- Step 1: search --}}
    @if ($step === 'search')
        <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Travel date</label>
                    <input type="date" wire:model.live="travelDate"
                        class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Search route</label>
                    <input type="text" wire:model.live.debounce.300ms="originFilter" placeholder="e.g. Bauchi, Jos..."
                        class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500">
                </div>
            </div>
        </div>

        <div class="space-y-3">
            @forelse ($this->availableTrips as $trip)
                <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-800">{{ $trip->route->label() }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $trip->departure_time->format('g:i A') }} Â·
                            {{ $trip->vehicle->plate_no }} ({{ ucfirst($trip->vehicle->type) }}) Â·
                            {{ $trip->seatsAvailable() }} seat(s) left
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-emerald-700">â‚¦{{ number_format($trip->route->fare, 2) }}</p>
                        <button wire:click="selectTrip({{ $trip->id }})" @disabled($trip->isFull())
                            class="mt-1 text-sm px-3 py-1.5 rounded-lg bg-emerald-600 text-white disabled:bg-slate-300 disabled:cursor-not-allowed hover:bg-emerald-700">
                            {{ $trip->isFull() ? 'Full' : 'Select' }}
                        </button>
                    </div>
                </div>
            @empty
                <div class="text-center text-slate-400 py-10 border border-dashed border-slate-200 rounded-xl">
                    No trips found for that date/route yet.
                </div>
            @endforelse
        </div>
    @endif

    {{-- Step 2: confirm --}}
    @if ($step === 'confirm' && $selectedTripId)
        @php($trip = \App\Models\Trip::with(['route', 'vehicle'])->find($selectedTripId))
        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h2 class="font-semibold text-slate-800">Confirm your seat</h2>
            <dl class="text-sm divide-y divide-slate-100">
                <div class="flex justify-between py-2"><dt class="text-slate-500">Route</dt><dd>{{ $trip->route->label() }}</dd></div>
                <div class="flex justify-between py-2"><dt class="text-slate-500">Departure</dt><dd>{{ $trip->departure_time->format('D, d M Y g:i A') }}</dd></div>
                <div class="flex justify-between py-2"><dt class="text-slate-500">Vehicle</dt><dd>{{ $trip->vehicle->plate_no }}</dd></div>
                <div class="flex justify-between py-2"><dt class="text-slate-500">Fare</dt><dd class="font-semibold text-emerald-700">â‚¦{{ number_format($trip->route->fare, 2) }}</dd></div>
            </dl>
            <div class="flex gap-3">
                <button wire:click="backToSearch" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-sm">Back</button>
                <button wire:click="confirmBooking" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Reserve seat</button>
            </div>
        </div>
    @endif

    {{-- Step 3: pay --}}
    @if ($step === 'pay' && $activeBooking)
        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            <h2 class="font-semibold text-slate-800">Pay for your booking</h2>
            <p class="text-sm text-slate-500">Reference: <span class="font-mono">{{ $activeBooking->reference }}</span> Â· Seat #{{ $activeBooking->seat_no }}</p>
            <p class="text-sm text-slate-500">This is a demo payment step â€” clicking "Pay now" simulates a successful transaction.</p>
            <button wire:click="pay" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Pay now</button>
        </div>
    @endif

    {{-- Step 4: paid --}}
    @if ($step === 'paid' && $activeBooking)
        <div class="bg-white rounded-xl border border-emerald-200 bg-emerald-50 p-6 space-y-4 text-center">
            <h2 class="font-semibold text-emerald-800 text-lg">Payment successful</h2>
            <p class="text-sm text-emerald-700">Your seat is confirmed. Your ticket is ready below.</p>
            <a href="{{ route('tickets.show', $activeBooking) }}" target="_blank"
                class="inline-block px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                View / print ticket
            </a>
            <div>
                <button wire:click="backToSearch" class="mt-2 text-sm text-slate-500 underline">Book another trip</button>
            </div>
        </div>
    @endif
</div>
