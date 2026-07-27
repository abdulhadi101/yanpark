<x-layouts.app>
    <div class="max-w-lg mx-auto space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800">Add park</h1>

        <form method="POST" action="{{ route('admin.parks.store') }}" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded-lg border-slate-300">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Location</label>
                <input type="text" name="location" value="{{ old('location') }}" class="w-full rounded-lg border-slate-300">
                @error('location') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Save</button>
        </form>
    </div>
</x-layouts.app>
