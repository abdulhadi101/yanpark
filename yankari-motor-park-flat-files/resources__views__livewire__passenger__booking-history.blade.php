{{-- File path: resources/views/livewire/passenger/booking-history.blade.php --}}
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-slate-800">My bookings</h1>
        <p class="text-slate-500 text-sm">Your booking history at Yankari Motor Park.</p>
    </div>

    @if (session('status'))
        <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
            {{ session('status') }}
        </div>
    @endif

    @error('history')
        <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
            {{ $message }}
        </div>
    @enderror

    <div class="space-y-3">
        @forelse ($this->bookings as $booking)
            <div class="bg-white rounded-xl border border-slate-200 p-4 flex items-center justify-between">
                <div>
                    <p class="font-medium text-slate-800">{{ $booking->trip->route->label() }}</p>
                    <p class="text-sm text-slate-500">
                        {{ $booking->trip->departure_time->format('D, d M Y g:i A') }} ·
                        Seat #{{ $booking->seat_no }} · Ref: {{ $booking->reference }}
                    </p>
                    <span class="inline-block mt-1 text-xs font-medium px-2 py-0.5 rounded-full
                        {{ match($booking->status) {
                            'paid' => 'bg-emerald-100 text-emerald-700',
                            'cancelled' => 'bg-slate-100 text-slate-500',
                            default => 'bg-amber-100 text-amber-700',
                        } }}">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>
                <div class="flex gap-3 items-center text-sm">
                    @if ($booking->status === 'paid')
                        <a href="{{ route('tickets.show', $booking) }}" target="_blank" class="text-emerald-700 underline">View ticket</a>
                    @elseif ($booking->status === 'pending')
                        <button wire:click="cancel({{ $booking->id }})" wire:confirm="Cancel this booking?"
                            class="text-red-600 underline">Cancel</button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-slate-400 py-10 border border-dashed border-slate-200 rounded-xl">
                No bookings yet.
                <a href="{{ route('passenger.book') }}" class="text-emerald-700 underline">Book a trip</a>
            </div>
        @endforelse
    </div>
</div>
