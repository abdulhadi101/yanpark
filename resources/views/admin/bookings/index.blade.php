<x-layouts.app>
    <div class="max-w-4xl mx-auto space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800">Bookings</h1>

        @if (session('status'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex gap-2 text-sm">
            <a href="{{ route('admin.bookings.index') }}"
                class="px-3 py-1.5 rounded-lg {{ $statusFilter === '' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">All</a>
            <a href="{{ route('admin.bookings.index', ['status' => 'pending']) }}"
                class="px-3 py-1.5 rounded-lg {{ $statusFilter === 'pending' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Pending</a>
            <a href="{{ route('admin.bookings.index', ['status' => 'paid']) }}"
                class="px-3 py-1.5 rounded-lg {{ $statusFilter === 'paid' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Paid</a>
            <a href="{{ route('admin.bookings.index', ['status' => 'cancelled']) }}"
                class="px-3 py-1.5 rounded-lg {{ $statusFilter === 'cancelled' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Cancelled</a>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
            @forelse ($bookings as $booking)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-800">{{ $booking->passengerName() }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $booking->trip->route->label() }} Â· Seat #{{ $booking->seat_no }} Â·
                            {{ $booking->trip->departure_time->format('d M, g:i A') }} Â· Ref: {{ $booking->reference }}
                            @if ($booking->isWalkIn())
                                <span class="text-slate-400">(walk-in)</span>
                            @endif
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-medium px-2 py-1 rounded-full
                            {{ match($booking->status) {
                                'paid' => 'bg-emerald-100 text-emerald-700',
                                'cancelled' => 'bg-slate-100 text-slate-500',
                                default => 'bg-amber-100 text-amber-700',
                            } }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                        @if ($booking->status === 'pending')
                            <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" onsubmit="return confirm('Cancel this booking?')">
                                @csrf
                                <button class="text-sm text-red-600 underline">Cancel</button>
                            </form>
                        @endif
                        @if ($booking->status === 'paid')
                            <a href="{{ route('tickets.show', $booking) }}" target="_blank" class="text-sm text-slate-600 underline">Ticket</a>
                        @endif
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-slate-400">No bookings found.</p>
            @endforelse
        </div>

        {{ $bookings->links() }}
    </div>
</x-layouts.app>
