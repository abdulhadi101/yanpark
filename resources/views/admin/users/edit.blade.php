@extends('layouts.app.sidebar')

@section('content')
    <main class="flex-1">
        <div class="max-w-2xl mx-auto space-y-6">
            <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Back to users</a>
            <h1 class="text-2xl font-semibold text-slate-800">Edit user</h1>

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
                @csrf @method('PUT')
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-600 mb-1">Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-600 mb-1">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="phone" class="block text-sm font-medium text-slate-600 mb-1">Phone</label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="role" class="block text-sm font-medium text-slate-600 mb-1">Role</label>
                    <select id="role" name="role" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                        <option value="passenger" @selected(old('role', $user->role) === 'passenger')>Passenger</option>
                        <option value="cashier" @selected(old('role', $user->role) === 'cashier')>Cashier</option>
                        <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                    </select>
                    @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-600 mb-1">New password (leave blank to keep current)</label>
                    <input id="password" type="password" name="password" class="w-full rounded-lg border border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 focus:ring-1">
                    @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Update account</button>
            </form>
        </div>
    </main>
@endsection
