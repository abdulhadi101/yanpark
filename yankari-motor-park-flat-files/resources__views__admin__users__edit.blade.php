{{-- File path: resources/views/admin/users/edit.blade.php --}}
<x-layouts.app>
    <div class="max-w-lg mx-auto space-y-6">
        <h1 class="text-2xl font-semibold text-slate-800">Edit user</h1>

        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white rounded-xl border border-slate-200 p-6 space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border-slate-300">
                @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border-slate-300">
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Phone</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border-slate-300">
                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">Role</label>
                <select name="role" class="w-full rounded-lg border-slate-300">
                    <option value="passenger" @selected(old('role', $user->role) === 'passenger')>Passenger</option>
                    <option value="cashier" @selected(old('role', $user->role) === 'cashier')>Cashier</option>
                    <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                </select>
                @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-600 mb-1">New password (leave blank to keep current)</label>
                <input type="password" name="password" class="w-full rounded-lg border-slate-300">
                @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">Update account</button>
        </form>
    </div>
</x-layouts.app>
