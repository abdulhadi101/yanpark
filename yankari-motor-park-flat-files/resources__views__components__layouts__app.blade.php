{{-- File path: resources/views/components/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Yankari Motor Park</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-50 min-h-screen">

    <nav class="bg-slate-800 text-white">
        <div class="max-w-5xl mx-auto px-4 flex items-center justify-between h-14">
            <span class="font-semibold">Yankari Motor Park</span>

            @auth
                <div class="flex items-center gap-4 text-sm">
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-emerald-300">Dashboard</a>
                        <a href="{{ route('admin.trips') }}" class="hover:text-emerald-300">Trips</a>
                        <a href="{{ route('admin.parks.index') }}" class="hover:text-emerald-300">Parks</a>
                        <a href="{{ route('admin.routes.index') }}" class="hover:text-emerald-300">Routes</a>
                        <a href="{{ route('admin.vehicles.index') }}" class="hover:text-emerald-300">Vehicles</a>
                        <a href="{{ route('admin.drivers.index') }}" class="hover:text-emerald-300">Drivers</a>
                        <a href="{{ route('admin.bookings.index') }}" class="hover:text-emerald-300">Bookings</a>
                        <a href="{{ route('admin.users.index') }}" class="hover:text-emerald-300">Users</a>
                    @elseif (auth()->user()->isCashier())
                        <a href="{{ route('cashier.counter') }}" class="hover:text-emerald-300">Counter</a>
                    @else
                        <a href="{{ route('passenger.book') }}" class="hover:text-emerald-300">Book a trip</a>
                        <a href="{{ route('passenger.bookings') }}" class="hover:text-emerald-300">My bookings</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="hover:text-red-300">Log out</button>
                    </form>
                </div>
            @endauth
        </div>
    </nav>

    <main class="py-8 px-4">
        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
