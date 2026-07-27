<x-layouts.app>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Drivers</h1>
            <a href="{{ route('admin.drivers.create') }}" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                Add driver
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
            @forelse ($drivers as $driver)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-800">{{ $driver->name }}</p>
                        <p class="text-sm text-slate-500">{{ $driver->phone }} · License: {{ $driver->license_no }} · {{ $driver->vehicles_count }} vehicle(s)</p>
                    </div>
                    <div class="flex gap-3 text-sm">
                        <a href="{{ route('admin.drivers.edit', $driver) }}" class="text-slate-600 underline">Edit</a>
                        <form method="POST" action="{{ route('admin.drivers.destroy', $driver) }}" onsubmit="return confirm('Delete this driver?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 underline">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-slate-400">No drivers yet.</p>
            @endforelse
        </div>

        {{ $drivers->links() }}
    </div>
</x-layouts.app>
