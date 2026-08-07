@extends('layouts.app.sidebar')

@section('content')
    <main class="flex-1">
        <div class="max-w-2xl mx-auto space-y-6">
            <a href="{{ route('admin.vehicles.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to vehicles</a>
            <h1 class="text-2xl font-semibold text-slate-800">Edit vehicle</h1>

            <form method="POST" action="{{ route('admin.vehicles.update', $vehicle) }}" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="park_id" class="block text-sm font-medium text-slate-600 mb-1">Park</label>
                    <select id="park_id" name="park_id" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                        @foreach ($parks as $park)
                            <option value="{{ $park->id }}" @selected(old('park_id', $vehicle->park_id) == $park->id)>{{ $park->name }}</option>
                        @endforeach
                    </select>
                    @error('park_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="driver_id" class="block text-sm font-medium text-slate-600 mb-1">Driver (optional)</label>
                    <select id="driver_id" name="driver_id" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                        <option value="">Unassigned</option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}" @selected(old('driver_id', $vehicle->driver_id) == $driver->id)>{{ $driver->name }}</option>
                        @endforeach
                    </select>
                    @error('driver_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="plate_no" class="block text-sm font-medium text-slate-600 mb-1">Plate number</label>
                        <input id="plate_no" type="text" name="plate_no" value="{{ old('plate_no', $vehicle->plate_no) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                        @error('plate_no') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="type" class="block text-sm font-medium text-slate-600 mb-1">Type</label>
                        <select id="type" name="type" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                            <option value="bus" @selected(old('type', $vehicle->type) === 'bus')>Bus</option>
                            <option value="car" @selected(old('type', $vehicle->type) === 'car')>Car</option>
                            <option value="coaster" @selected(old('type', $vehicle->type) === 'coaster')>Coaster</option>
                        </select>
                        @error('type') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="capacity" class="block text-sm font-medium text-slate-600 mb-1">Capacity (seats)</label>
                    <input id="capacity" type="number" name="capacity" value="{{ old('capacity', $vehicle->capacity) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('capacity') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Update</button>
            </form>
        </div>
    </main>
@endsection
