<x-layouts.app>
    <div class="max-w-lg mx-auto space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800">Add route</h1>

        <form method="POST" action="{{ route('admin.routes.store') }}" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
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
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Origin</label>
                    <input type="text" name="origin" value="{{ old('origin') }}" class="w-full rounded-lg border-slate-300">
                    @error('origin') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-600 mb-1">Destination</label>
                    <input type="text" name="destination" value="{{ old('destination') }}" class="w-full rounded-lg border-slate-300">
                    @error('destination') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Fare (â‚¦)</label>
                <input type="number" step="0.01" name="fare" value="{{ old('fare') }}" class="w-full rounded-lg border-slate-300">
                @error('fare') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Save</button>
        </form>
    </div>
</x-layouts.app>
