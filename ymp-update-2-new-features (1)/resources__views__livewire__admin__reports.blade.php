{{-- File path: resources/views/livewire/admin/reports.blade.php --}}
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between print:hidden">
        <div>
            <h1 class="text-2xl font-semibold text-slate-800">Reports</h1>
            <p class="text-slate-500 text-sm">Daily, weekly, and monthly revenue reports.</p>
        </div>
        <button onclick="window.print()" class="px-4 py-2 rounded-lg border border-slate-300 text-slate-600 text-sm hover:bg-slate-50">
            Print report
        </button>
    </div>

    <div class="flex gap-2 text-sm print:hidden">
        <button wire:click="$set('period', 'daily')"
            class="px-3 py-1.5 rounded-lg {{ $period === 'daily' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">
            Daily
        </button>
        <button wire:click="$set('period', 'weekly')"
            class="px-3 py-1.5 rounded-lg {{ $period === 'weekly' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">
            Weekly
        </button>
        <button wire:click="$set('period', 'monthly')"
            class="px-3 py-1.5 rounded-lg {{ $period === 'monthly' ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-600' }}">
            Monthly
        </button>
    </div>

    <div class="text-sm text-slate-500">
        {{ $summary['label'] }} — {{ $summary['start'] }}
        @if ($summary['start'] !== $summary['end'])
            to {{ $summary['end'] }}
        @endif
    </div>

    <div class="grid sm:grid-cols-3 gap-4">
        <x-stat-card label="Revenue" :value="'₦'.number_format($summary['revenue'], 2)" />
        <x-stat-card label="Paid bookings" :value="$summary['bookings']" />
        <x-stat-card label="Trips" :value="$summary['trips']" />
    </div>

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 text-slate-500 text-left">
                <tr>
                    <th class="px-4 py-2 font-medium">Date</th>
                    <th class="px-4 py-2 font-medium">Revenue</th>
                    <th class="px-4 py-2 font-medium">Paid bookings</th>
                    <th class="px-4 py-2 font-medium">Trips</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($summary['breakdown'] as $day)
                    <tr>
                        <td class="px-4 py-2 text-slate-700">{{ \Illuminate\Support\Carbon::parse($day['date'])->format('D, d M Y') }}</td>
                        <td class="px-4 py-2 text-slate-700">₦{{ number_format($day['revenue'], 2) }}</td>
                        <td class="px-4 py-2 text-slate-700">{{ $day['bookings'] }}</td>
                        <td class="px-4 py-2 text-slate-700">{{ $day['trips'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
