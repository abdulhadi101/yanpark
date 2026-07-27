<x-layouts.app>
    <div class="max-w-4xl mx-auto space-y-8">

        <div class="text-center py-10 bg-white rounded-xl border border-slate-200">
            <h1 class="text-3xl font-semibold text-slate-800">Welcome to Yankari Motor Park</h1>
            <p class="text-slate-500 mt-2">Book your seat online and skip the queue at the park.</p>
            <div class="mt-6 flex items-center justify-center gap-3">
                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                    Create an account
                </a>
                <a href="{{ route('login') }}" class="px-5 py-2.5 rounded-lg border border-slate-300 text-slate-600 text-sm hover:bg-slate-50">
                    Login
                </a>
            </div>
        </div>

        <div>
            <h2 class="font-medium text-slate-700 mb-3">Available routes</h2>
            <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
                @forelse ($routes as $route)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-800">{{ $route->label() }}</p>
                            <p class="text-sm text-slate-500">From {{ $route->park->name }}</p>
                        </div>
                        <p class="font-semibold text-emerald-700">₦{{ number_format($route->fare, 2) }}</p>
                    </div>
                @empty
                    <p class="p-6 text-center text-slate-400">No routes published yet — check back soon.</p>
                @endforelse
            </div>
            <p class="text-sm text-slate-400 mt-3 text-center">
                <a href="{{ route('register') }}" class="text-emerald-700 underline">Register</a> or
                <a href="{{ route('login') }}" class="text-emerald-700 underline">login</a> to book a seat and view live schedules.
            </p>
        </div>
    </div>
</x-layouts.app>
