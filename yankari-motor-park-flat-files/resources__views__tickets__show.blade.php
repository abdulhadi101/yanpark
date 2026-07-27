{{-- File path: resources/views/tickets/show.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Ticket {{ $booking->reference }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-6">

    <div class="bg-white w-full max-w-md rounded-xl border border-slate-200 p-6 space-y-4">
        <div class="text-center border-b border-dashed border-slate-300 pb-4">
            <p class="text-xs uppercase tracking-widest text-slate-400">Yankari Motor Park</p>
            <h1 class="text-lg font-semibold text-slate-800">E-Ticket</h1>
        </div>

        <dl class="text-sm divide-y divide-slate-100">
            <div class="flex justify-between py-2"><dt class="text-slate-500">Reference</dt><dd class="font-mono">{{ $booking->reference }}</dd></div>
            <div class="flex justify-between py-2"><dt class="text-slate-500">Passenger</dt><dd>{{ $booking->passengerName() }}</dd></div>
            <div class="flex justify-between py-2"><dt class="text-slate-500">Route</dt><dd>{{ $booking->trip->route->label() }}</dd></div>
            <div class="flex justify-between py-2"><dt class="text-slate-500">Departure</dt><dd>{{ $booking->trip->departure_time->format('D, d M Y g:i A') }}</dd></div>
            <div class="flex justify-between py-2"><dt class="text-slate-500">Vehicle</dt><dd>{{ $booking->trip->vehicle->plate_no }}</dd></div>
            <div class="flex justify-between py-2"><dt class="text-slate-500">Seat</dt><dd>#{{ $booking->seat_no }}</dd></div>
            <div class="flex justify-between py-2"><dt class="text-slate-500">Status</dt>
                <dd class="font-semibold {{ $booking->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                    {{ ucfirst($booking->status) }}
                </dd>
            </div>
            @if ($booking->payment)
                <div class="flex justify-between py-2"><dt class="text-slate-500">Amount paid</dt><dd>₦{{ number_format($booking->payment->amount, 2) }}</dd></div>
            @endif
        </dl>

        <button onclick="window.print()" class="w-full py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700 print:hidden">
            Print ticket
        </button>
    </div>
</body>
</html>
