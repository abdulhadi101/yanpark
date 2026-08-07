@extends('layouts.app.sidebar')

@section('content')
    <main class="flex-1">
        <div class="max-w-5xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-slate-800">Users</h1>
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                    Add user
                </a>
            </div>

            @if (session('status'))
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
                @forelse ($users as $user)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-800">{{ $user->name }}</p>
                            <p class="text-sm text-slate-500">{{ $user->email }} · {{ $user->phone ?? 'No phone on file' }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-medium px-2 py-1 rounded-full
                                {{ match($user->role) {
                                    'admin' => 'bg-purple-100 text-purple-700',
                                    'cashier' => 'bg-blue-100 text-blue-700',
                                    default => 'bg-slate-100 text-slate-600',
                                } }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-sm text-slate-600 underline">Edit</a>
                            @if ($user->id !== auth()->id())
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Remove this user account?')">
                                    @csrf @method('DELETE')
                                    <button class="text-sm text-red-600 underline">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-500">
                        No users found.
                    </div>
                @endforelse
            </div>

            {{ $users->links() }}
        </div>
    </main>
@endsection
