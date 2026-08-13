<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white" x-data="{ sidebarOpen: false }">
        @php
            $adminLinks = [
                ['route' => 'admin.dashboard', 'icon' => 'home', 'label' => __('Dashboard'), 'pattern' => 'admin.dashboard'],
                ['route' => 'admin.parks.index', 'icon' => 'map-pin', 'label' => __('Parks'), 'pattern' => 'admin.parks.*'],
                ['route' => 'admin.routes.index', 'icon' => 'sign-post', 'label' => __('Routes'), 'pattern' => 'admin.routes.*'],
                ['route' => 'admin.vehicles.index', 'icon' => 'truck', 'label' => __('Vehicles'), 'pattern' => 'admin.vehicles.*'],
                ['route' => 'admin.drivers.index', 'icon' => 'user', 'label' => __('Drivers'), 'pattern' => 'admin.drivers.*'],
                ['route' => 'admin.bookings.index', 'icon' => 'ticket', 'label' => __('Bookings'), 'pattern' => 'admin.bookings.*'],
                ['route' => 'admin.users.index', 'icon' => 'users', 'label' => __('Users'), 'pattern' => 'admin.users.*'],
                ['route' => 'admin.trips', 'icon' => 'route', 'label' => __('Trips'), 'pattern' => 'admin.trips'],
                ['route' => 'admin.reports', 'icon' => 'chart-bar', 'label' => __('Reports'), 'pattern' => 'admin.reports'],
            ];
            $passengerLinks = [
                ['route' => 'passenger.book', 'icon' => 'ticket', 'label' => __('Book a trip'), 'pattern' => 'passenger.book'],
                ['route' => 'passenger.bookings', 'icon' => 'list-bullet', 'label' => __('My bookings'), 'pattern' => 'passenger.bookings'],
            ];
        @endphp

        <div class="flex min-h-screen">
            {{-- Desktop Sidebar --}}
            <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:border-e lg:border-zinc-200 lg:bg-zinc-50 lg:fixed lg:inset-y-0">
                <div class="flex items-center gap-3 h-16 px-4 border-b border-zinc-200 shrink-0">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5">
                        <span class="flex items-center justify-center size-8 rounded-lg bg-zinc-900 text-white shrink-0">
                            <x-app-logo-icon class="size-5 fill-current" />
                        </span>
                        <span class="font-semibold text-zinc-900 text-lg tracking-tight">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
                    @auth
                        @if (auth()->user()->isAdmin())
                            @include('partials._sidebar-nav', ['heading' => __('Admin'), 'links' => $adminLinks])
                        @elseif (auth()->user()->isCashier())
                            @include('partials._sidebar-nav', ['heading' => __('Cashier'), 'links' => [['route' => 'cashier.counter', 'icon' => 'calculator', 'label' => __('Counter'), 'pattern' => 'cashier.counter']]])
                        @else
                            @include('partials._sidebar-nav', ['heading' => __('Passenger'), 'links' => $passengerLinks])
                        @endif
                    @else
                        @include('partials._sidebar-nav', ['heading' => __('Welcome'), 'links' => [
                            ['route' => 'login', 'icon' => 'arrow-right-end-on-rectangle', 'label' => __('Login'), 'pattern' => 'login'],
                            ['route' => 'register', 'icon' => 'user-plus', 'label' => __('Register'), 'pattern' => 'register'],
                        ]])
                    @endauth
                </nav>

                {{-- Desktop User Menu --}}
                @auth
                <div class="border-t border-zinc-200 p-3 shrink-0 hidden lg:block space-y-1">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <span class="flex items-center justify-center size-8 rounded-full bg-zinc-200 text-zinc-600 text-sm font-semibold shrink-0">
                            {{ auth()->user()->initials() }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-zinc-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-2 px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-100 rounded-lg transition-colors">
                        <x-icon name="cog" class="size-4 shrink-0" />
                        {{ __('Settings') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer">
                            <x-icon name="arrow-right-start-on-rectangle" class="size-4 shrink-0" />
                            {{ __('Log out') }}
                        </button>
                    </form>
                </div>
                @endauth
            </aside>

            {{-- Mobile Sidebar Overlay --}}
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-40 bg-zinc-900/50 lg:hidden"></div>

            {{-- Mobile Sidebar --}}
            <aside x-show="sidebarOpen" x-transition:enter="transition-transform ease-in-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                   x-transition:leave="transition-transform ease-in-out duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                   x-cloak class="fixed inset-y-0 left-0 z-50 w-64 bg-zinc-50 border-e border-zinc-200 lg:hidden flex flex-col">
                <div class="flex items-center gap-3 h-16 px-4 border-b border-zinc-200 shrink-0">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2.5" @click="sidebarOpen = false">
                        <span class="flex items-center justify-center size-8 rounded-lg bg-zinc-900 text-white shrink-0">
                            <x-app-logo-icon class="size-5 fill-current" />
                        </span>
                        <span class="font-semibold text-zinc-900 text-lg tracking-tight">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    <button @click="sidebarOpen = false" class="ml-auto p-1 rounded-md text-zinc-500 hover:text-zinc-700 cursor-pointer">
                        <x-icon name="x-mark" class="size-5" />
                    </button>
                </div>

                <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
                    @auth
                        @if (auth()->user()->isAdmin())
                            @include('partials._sidebar-nav', ['heading' => __('Admin'), 'links' => $adminLinks, 'mobile' => true])
                        @elseif (auth()->user()->isCashier())
                            @include('partials._sidebar-nav', ['heading' => __('Cashier'), 'links' => [['route' => 'cashier.counter', 'icon' => 'calculator', 'label' => __('Counter'), 'pattern' => 'cashier.counter']], 'mobile' => true])
                        @else
                            @include('partials._sidebar-nav', ['heading' => __('Passenger'), 'links' => $passengerLinks, 'mobile' => true])
                        @endif
                    @else
                        @include('partials._sidebar-nav', ['heading' => __('Welcome'), 'links' => [
                            ['route' => 'login', 'icon' => 'arrow-right-end-on-rectangle', 'label' => __('Login'), 'pattern' => 'login'],
                            ['route' => 'register', 'icon' => 'user-plus', 'label' => __('Register'), 'pattern' => 'register'],
                        ], 'mobile' => true])
                    @endauth
                </nav>

                @auth
                <div class="border-t border-zinc-200 p-3 shrink-0 space-y-1">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <span class="flex items-center justify-center size-8 rounded-full bg-zinc-200 text-zinc-600 text-sm font-semibold shrink-0">
                            {{ auth()->user()->initials() }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-zinc-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-zinc-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" wire:navigate @click="sidebarOpen = false" class="flex items-center gap-2 px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-100 rounded-lg transition-colors">
                        <x-icon name="cog" class="size-4 shrink-0" />
                        {{ __('Settings') }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-lg transition-colors cursor-pointer">
                            <x-icon name="arrow-right-start-on-rectangle" class="size-4 shrink-0" />
                            {{ __('Log out') }}
                        </button>
                    </form>
                </div>
                @endauth
            </aside>

            {{-- Main Content --}}
            <div class="flex-1 lg:pl-64">
                {{-- Mobile Header --}}
                <div class="sticky top-0 z-30 flex items-center h-16 px-4 bg-white border-b border-zinc-200 lg:hidden">
                    <button @click="sidebarOpen = true" class="p-2 -ml-2 rounded-md text-zinc-500 hover:text-zinc-700 cursor-pointer">
                        <x-icon name="bars-2" class="size-5" />
                    </button>
                    <div class="flex-1"></div>
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-2">
                        <span class="flex items-center justify-center size-8 rounded-lg bg-zinc-900 text-white shrink-0">
                            <x-app-logo-icon class="size-5 fill-current" />
                        </span>
                        <span class="font-semibold text-zinc-900 text-lg tracking-tight">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    <div class="flex-1"></div>
                </div>

                @yield('content', $slot ?? '')
            </div>
        </div>

        @persist('toast')
            @if (session('status'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                     class="fixed bottom-4 right-4 z-50 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
                    <x-icon name="check-circle" class="size-5 shrink-0" />
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                    <button @click="show = false" class="ml-2 text-emerald-600 hover:text-emerald-800 cursor-pointer">
                        <x-icon name="x-mark" class="size-4" />
                    </button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)" x-transition
                     class="fixed bottom-4 right-4 z-50 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-lg flex items-center gap-2">
                    <x-icon name="x-circle" class="size-5 shrink-0" />
                    <span class="text-sm font-medium">{{ session('error') }}</span>
                    <button @click="show = false" class="ml-2 text-red-600 hover:text-red-800 cursor-pointer">
                        <x-icon name="x-mark" class="size-4" />
                    </button>
                </div>
            @endif
        @endpersist
    </body>
</html>
