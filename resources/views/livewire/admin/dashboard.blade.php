@php($maxRevenue = max(1, collect($revenueLastDays)->max('total')))

<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-slate-800">Admin dashboard</h1>
        <p class="text-slate-500 text-sm">Yankari Motor Park — overview</p>
    </div>

    <div class="grid sm:grid-cols-4 gap-4">
        <x-stat-card label="Total users" :value="$totalUsers" />
        <x-stat-card label="Total buses" :value="$totalVehicles" />
        <x-stat-card label="Total trips" :value="$totalTrips" />
        <x-stat-card label="Total bookings" :value="$totalBookings" />
    </div>

    <div class="grid sm:grid-cols-4 gap-4">
        <x-stat-card label="Revenue today" :value="'₦'.number_format($todayRevenue, 2)" />
        <x-stat-card label="Total revenue" :value="'₦'.number_format($totalRevenue, 2)" />
        <x-stat-card label="Trips today" :value="$tripsToday" />
        <x-stat-card label="Passengers today" :value="$passengersToday" />
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-medium text-slate-700 mb-4">Revenue — last 7 days</h2>
        <div class="flex items-end gap-3 h-40">
            @foreach ($revenueLastDays as $day)
                @php($heightPct = $day['total'] > 0 ? max(6, ($day['total'] / $maxRevenue) * 100) : 3)
                <div class="flex-1 flex flex-col items-center gap-2">
                    <div class="w-full bg-emerald-500 rounded-t" style="height: {{ $heightPct }}%"
                        title="₦{{ number_format($day['total'], 2) }}"></div>
                    <span class="text-xs text-slate-400">{{ \Illuminate\Support\Carbon::parse($day['date'])->format('D') }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-5">
        <h2 class="font-medium text-slate-700 mb-4">Most popular routes</h2>
        @forelse ($popularRoutes as $route)
            <div class="flex justify-between py-2 border-b border-slate-100 last:border-0 text-sm">
                <span class="text-slate-700">{{ $route->label }}</span>
                <span class="text-slate-500">{{ $route->trips }} trip(s)</span>
            </div>
        @empty
            <p class="text-slate-400 text-sm">No trips recorded yet.</p>
        @endforelse
    </div>
</div>
