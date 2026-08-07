@extends('layouts.app.sidebar')

@section('content')
    <main class="flex-1">
        <div class="max-w-5xl mx-auto space-y-6">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-slate-800">Parks</h1>
                <a href="{{ route('admin.parks.create') }}" class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm hover:bg-emerald-700">
                    Add park
                </a>
            </div>

            @if (session('status'))
                <div class="rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white rounded-xl border border-slate-200 divide-y divide-slate-100">
                @forelse ($parks as $park)
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <p class="font-medium text-slate-800">{{ $park->name }}</p>
                            <p class="text-sm text-slate-500">{{ $park->location }} · {{ $park->vehicles_count }} vehicle(s) · {{ $park->routes_count }} route(s)</p>
                        </div>
                        <div class="flex gap-3 text-sm">
                            <a href="{{ route('admin.parks.edit', $park) }}" class="text-slate-600 underline">Edit</a>
                            <form method="POST" action="{{ route('admin.parks.destroy', $park) }}" onsubmit="return confirm('Delete this park?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 underline">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="p-6 text-center text-slate-400">No parks yet.</p>
                @endforelse
            </div>

            {{ $parks->links() }}
        </div>
    </main>
@endsection
