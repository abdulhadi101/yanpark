<x-layouts.app>
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-slate-800">Vehicles</h1>
            <a href="{{ route('admin.vehicles.create') }}" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                Add vehicle
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                {{ session('status') }}
            </div>
        @endif

        <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
            @forelse ($vehicles as $vehicle)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-slate-800">{{ $vehicle->plate_no }} Â· {{ ucfirst($vehicle->type) }}</p>
                        <p class="text-sm text-slate-500">
                            {{ $vehicle->park->name }} Â· {{ $vehicle->capacity }} seats Â·
                            Driver: {{ $vehicle->driver->name ?? 'Unassigned' }}
                        </p>
                    </div>
                    <div class="flex gap-3 text-sm">
                        <a href="{{ route('admin.vehicles.edit', $vehicle) }}" class="text-slate-600 underline">Edit</a>
                        <form method="POST" action="{{ route('admin.vehicles.destroy', $vehicle) }}" onsubmit="return confirm('Delete this vehicle?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 underline">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="p-6 text-center text-slate-400">No vehicles yet.</p>
            @endforelse
        </div>

        {{ $vehicles->links() }}
    </div>
</x-layouts.app>
