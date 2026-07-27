{{-- File path: resources/views/admin/vehicles/create.blade.php --}}
<x-layouts.app>
    <div class="max-w-lg mx-auto space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800">Add vehicle</h1>

        <form method="POST" action="{{ route('admin.vehicles.store') }}" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Park</label>
                <select name="park_id" class="w-full rounded-lg border-slate-300">
                    @foreach ($parks as $park)
                        <option value="{{ $park->id }}" @selected(old('park_id') == $park->id)>{{ $park->name }}</option>
                    @endforeach
                </select>
                @error('park_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Driver (optional)</label>
                <select name="driver_id" class="w-full rounded-lg border-slate-300">
                    <option value="">Unassigned</option>
                    @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}" @selected(old('driver_id') == $driver->id)>{{ $driver->name }}</option>
                    @endforeach
                </select>
                @error('driver_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Plate number</label>
                    <input type="text" name="plate_no" value="{{ old('plate_no') }}" class="w-full rounded-lg border-slate-300">
                    @error('plate_no') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Type</label>
                    <select name="type" class="w-full rounded-lg border-slate-300">
                        <option value="bus" @selected(old('type') === 'bus')>Bus</option>
                        <option value="car" @selected(old('type') === 'car')>Car</option>
                        <option value="coaster" @selected(old('type') === 'coaster')>Coaster</option>
                    </select>
                    @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Capacity (seats)</label>
                <input type="number" name="capacity" value="{{ old('capacity', 14) }}" class="w-full rounded-lg border-slate-300">
                @error('capacity') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Save</button>
        </form>
    </div>
</x-layouts.app>
