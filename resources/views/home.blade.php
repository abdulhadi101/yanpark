@extends('layouts.app.sidebar')

@section('content')
    <main class="flex-1">
        {{-- Hero Section --}}
        <div class="relative overflow-hidden">
            <div class="absolute inset-0">
                <img src="{{ asset('yankari.jpg') }}" alt="Yankari Motor Park fleet" class="h-full w-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-r from-zinc-900/80 via-zinc-900/50 to-transparent"></div>
            </div>

            <div class="relative px-6 py-16 sm:px-12 sm:py-24 lg:px-16 lg:py-32">
                <div class="max-w-2xl">
                    <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl">
                        Yankari Motor Park
                    </h1>
                    <p class="mt-4 text-lg leading-8 text-zinc-200">
                        Book your seat online and skip the queue. Fast, reliable inter-state transport at your fingertips.
                    </p>
                    <div class="mt-8 flex items-center gap-4">
                        <a href="{{ route('register') }}" wire:navigate
                           class="rounded-lg bg-emerald-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-colors">
                            Create an account
                        </a>
                        <a href="{{ route('login') }}" wire:navigate
                           class="rounded-lg border border-white/30 px-6 py-3 text-sm font-semibold text-white hover:bg-white/10 transition-colors">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Available Routes --}}
        <div class="px-6 py-12 sm:px-12 lg:px-16">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-xl font-semibold text-zinc-900 mb-6">Available Routes</h2>
                <div class="bg-white rounded-xl border border-zinc-200 divide-y divide-zinc-100 shadow-sm">
                    @forelse ($routes as $route)
                        <div class="p-5 flex items-center justify-between">
                            <div>
                                <p class="font-medium text-zinc-800">{{ $route->label() }}</p>
                                <p class="text-sm text-zinc-500 mt-0.5">From {{ $route->park->name }}</p>
                            </div>
                            <p class="font-semibold text-emerald-700 text-lg">₦{{ number_format($route->fare, 2) }}</p>
                        </div>
                    @empty
                        <div class="p-8 text-center text-zinc-400">
                            No routes published yet — check back soon.
                        </div>
                    @endforelse
                </div>
                <p class="text-sm text-zinc-400 mt-4 text-center">
                    <a href="{{ route('register') }}" wire:navigate class="text-emerald-600 hover:text-emerald-700 underline">Register</a> or
                    <a href="{{ route('login') }}" wire:navigate class="text-emerald-600 hover:text-emerald-700 underline">login</a> to book a seat and view live schedules.
                </p>
            </div>
        </div>
    </main>
@endsection
