{{-- File path: resources/views/livewire/admin/trip-manager.blade.php --}}
<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Manage trips</h1>
        <p class="text-slate-500 text-sm">Open trips for loading, dispatch them, and mark them complete.</p>
    </div>

    @if (session('status'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 p-5 space-y-4">
        <div>
            <label class="block text-sm font-medium text-slate-600 mb-1">Park</label>
            <select wire:model.live="parkId" class="w-full rounded-lg border-slate-300">
                @foreach ($this->parks as $park)
                    <option value="{{ $park->id }}">{{ $park->name }} — {{ $park->location }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Vehicle</label>
                <select wire:model="vehicleId" class="w-full rounded-lg border-slate-300">
                    <option value="">Select vehicle</option>
                    @foreach ($this->vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}">{{ $vehicle->plate_no }} ({{ $vehicle->capacity }} seats)</option>
                    @endforeach
                </select>
                @error('vehicleId') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Route</label>
                <select wire:model="routeId" class="w-full rounded-lg border-slate-300">
                    <option value="">Select route</option>
                    @foreach ($this->routes as $route)
                        <option value="{{ $route->id }}">{{ $route->label() }} (₦{{ number_format($route->fare, 0) }})</option>
                    @endforeach
                </select>
                @error('routeId') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Departure</label>
                <input type="datetime-local" wire:model="departureTime" class="w-full rounded-lg border-slate-300">
                @error('departureTime') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <button wire:click="createTrip" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
            Open trip for loading
        </button>
    </div>

    <div class="space-y-3">
        <h2 class="font-medium text-slate-700">Active trips</h2>

        @forelse ($this->activeTrips as $trip)
            <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-slate-800">
                        {{ $trip->route->label() }}
                        <span class="ml-2 text-xs font-normal px-2 py-0.5 rounded-full
                            {{ $trip->status === 'loading' ? 'bg-amber-100 text-amber-700' : 'bg-blue-100 text-blue-700' }}">
                            {{ ucfirst($trip->status) }}
                        </span>
                    </p>
                    <p class="text-sm text-slate-500">
                        {{ $trip->vehicle->plate_no }} · {{ $trip->departure_time->format('g:i A') }} ·
                        {{ $trip->seats_booked }}/{{ $trip->vehicle->capacity }} seats booked
                    </p>
                </div>
                <div class="flex gap-2">
                    @if ($trip->status === 'loading')
                        <button wire:click="dispatch_({{ $trip->id }})"
                            class="px-3 py-1.5 rounded-lg bg-slate-800 text-white text-sm hover:bg-slate-900">
                            Dispatch
                        </button>
                        <button wire:click="cancelTrip({{ $trip->id }})" wire:confirm="Cancel this trip?"
                            class="px-3 py-1.5 rounded-lg border border-red-200 text-red-600 text-sm hover:bg-red-50">
                            Cancel
                        </button>
                    @else
                        <button wire:click="complete({{ $trip->id }})"
                            class="px-3 py-1.5 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                            Mark completed
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-slate-400 py-10 border border-dashed border-slate-200 rounded-xl">
                No active trips — open one above.
            </div>
        @endforelse
    </div>
</div>
