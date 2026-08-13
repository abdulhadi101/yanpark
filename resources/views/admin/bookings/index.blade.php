@extends('layouts.app.sidebar')

@section('content')
    <main class="flex-1">
        <div class="max-w-5xl mx-auto space-y-6">
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

            <div class="flex flex-wrap gap-2 text-sm">
                <a href="{{ route('admin.bookings.index', request()->except(['status', 'page'])) }}"
                    class="px-3 py-1.5 rounded-lg {{ $statusFilter === '' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">All</a>
                <a href="{{ route('admin.bookings.index', ['status' => 'pending'] + request()->except(['status', 'page'])) }}"
                    class="px-3 py-1.5 rounded-lg {{ $statusFilter === 'pending' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Pending</a>
                <a href="{{ route('admin.bookings.index', ['status' => 'paid'] + request()->except(['status', 'page'])) }}"
                    class="px-3 py-1.5 rounded-lg {{ $statusFilter === 'paid' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Paid</a>
                <a href="{{ route('admin.bookings.index', ['status' => 'cancelled'] + request()->except(['status', 'page'])) }}"
                    class="px-3 py-1.5 rounded-lg {{ $statusFilter === 'cancelled' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">Cancelled</a>
            </div>

            <form method="GET" action="{{ route('admin.bookings.index') }}"
                class="flex flex-wrap items-center gap-2 text-sm">
                @if ($statusFilter !== '')
                    <input type="hidden" name="status" value="{{ $statusFilter }}">
                @endif
                <input type="search" name="search" value="{{ $search }}" placeholder="Search name, ref, phone or seat"
                    class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                <input type="date" name="from" value="{{ $from }}"
                    class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                <input type="date" name="to" value="{{ $to }}"
                    class="px-3 py-1.5 rounded-lg border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-slate-300">
                <button class="px-3 py-1.5 rounded-lg bg-slate-800 text-white">Filter</button>
                @if ($search !== '' || $from !== '' || $to !== '')
                    <a href="{{ route('admin.bookings.index', $statusFilter !== '' ? ['status' => $statusFilter] : []) }}"
                        class="text-slate-500 underline">Clear</a>
                @endif
            </form>

            <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
                @forelse ($bookings as $booking)
                    <div class="p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div class="min-w-0">
                                <p class="font-medium text-slate-800">
                                    {{ $booking->passengerName() }}
                                    @if ($booking->isWalkIn())
                                        <span class="text-xs font-normal text-slate-400">(walk-in)</span>
                                    @endif
                                </p>
                                <p class="text-sm text-slate-500 truncate">
                                    {{ $booking->passengerPhone() ?? 'No phone' }}
                                    @if ($booking->user?->email)
                                        · {{ $booking->user->email }}
                                    @endif
                                </p>
                                <p class="text-sm text-slate-500">
                                    {{ $booking->trip->route->label() }} · Seat #{{ $booking->seat_no }} ·
                                    {{ $booking->trip->departure_time->format('d M, g:i A') }} · Ref: {{ $booking->reference }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    Booked {{ $booking->created_at->format('d M, Y g:i A') }}
                                    @if ($booking->payment)
                                        · ₦{{ number_format($booking->payment->amount, 2) }}
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
                    </div>
                @empty
                    <p class="p-6 text-center text-slate-400">No bookings found.</p>
                @endforelse
            </div>

            {{ $bookings->links() }}
        </div>
    </main>
@endsection
