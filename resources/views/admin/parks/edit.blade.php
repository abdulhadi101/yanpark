@extends('layouts.app.sidebar')

@section('content')
    <main class="flex-1">
        <div class="max-w-2xl mx-auto space-y-6">
            <a href="{{ route('admin.parks.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to parks</a>
            <h1 class="text-2xl font-semibold text-slate-800">Edit park</h1>

            <form method="POST" action="{{ route('admin.parks.update', $park) }}" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-600 mb-1">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $park->name) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="location" class="block text-sm font-medium text-slate-600 mb-1">Location</label>
                    <input id="location" type="text" name="location" value="{{ old('location', $park->location) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('location') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Update</button>
            </form>
        </div>
    </main>
@endsection
