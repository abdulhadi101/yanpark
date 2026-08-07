@extends('layouts.app.sidebar')

@section('content')
    <main class="flex-1">
        <div class="max-w-2xl mx-auto space-y-6">
            <a href="{{ route('admin.drivers.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to drivers</a>
            <h1 class="text-2xl font-semibold text-slate-800">Edit driver</h1>

            <form method="POST" action="{{ route('admin.drivers.update', $driver) }}" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-600 mb-1">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $driver->name) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-600 mb-1">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $driver->phone) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="license_no" class="block text-sm font-medium text-slate-600 mb-1">License number</label>
                    <input id="license_no" type="text" name="license_no" value="{{ old('license_no', $driver->license_no) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('license_no') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Update</button>
            </form>
        </div>
    </main>
@endsection
