<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white" x-data="{ sidebarOpen: false }">
        <header class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-zinc-200 bg-zinc-50 px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
            <button type="button" x-on:click="sidebarOpen = true" class="lg:hidden -m-2.5 p-2.5 text-zinc-700">
                <span class="sr-only">Open sidebar</span>
                <x-icon name="bars-2" class="size-5" />
            </button>

            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

            <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                <div class="flex-1"></div>
                <div class="flex items-center gap-x-4 lg:gap-x-6">
                    <x-desktop-user-menu />
                </div>
            </div>
        </header>

        <!-- Mobile sidebar -->
        <div x-show="sidebarOpen" x-cloak class="relative z-50 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="fixed inset-0 bg-zinc-900/80" x-on:click="sidebarOpen = false"></div>
            <div class="fixed inset-0 flex">
                <div class="mr-16 w-full max-w-xs" x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in-out duration-300 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full">
                    <div class="flex h-full flex-col gap-y-5 overflow-y-auto bg-white px-6 pb-4 border-r border-zinc-200">
                        <div class="flex h-16 items-center justify-between">
                            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                            <button type="button" x-on:click="sidebarOpen = false" class="-m-2.5 p-2.5 text-zinc-700">
                                <span class="sr-only">Close sidebar</span>
                                <x-icon name="x-mark" class="size-5" />
                            </button>
                        </div>
                        <nav class="flex flex-1 flex-col">
                            <ul class="flex flex-1 flex-col gap-y-7" role="list">
                                    @auth
                                        @include('partials._sidebar-nav', ['heading' => 'Admin', 'links' => auth()->user()->isAdmin() ? [] : [], 'mobile' => true])
                                    @endauth
                                </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        {{ $slot }}

        @persist('toast')
            <div x-data="{ toasts: [], show(msg, type = 'success') { const id = Date.now(); this.toasts.push({ id, msg, type }); setTimeout(() => this.dismiss(id), 5000) }, dismiss(id) { this.toasts = this.toasts.filter(t => t.id !== id) } }"
                 x-on:toast.window="show($event.detail.msg, $event.detail.type)"
                 class="fixed inset-x-0 bottom-0 z-50 flex items-end justify-end p-4 pointer-events-none sm:items sm:p-6">
                <div class="flex w-full flex-col items-end gap-2">
                    <template x-for="toast in toasts" :key="toast.id">
                        <div x-show="true" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                             class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black/5">
                            <div class="p-4 flex items-start gap-3">
                                <template x-if="toast.type === 'success'">
                                    <x-icon name="check-circle" class="size-5 text-emerald-500 shrink-0 mt-0.5" />
                                </template>
                                <template x-if="toast.type === 'error'">
                                    <x-icon name="x-circle" class="size-5 text-red-500 shrink-0 mt-0.5" />
                                </template>
                                <p class="flex-1 text-sm text-zinc-700" x-text="toast.msg"></p>
                                <button x-on:click="dismiss(toast.id)" class="shrink-0 text-zinc-400 hover:text-zinc-600">
                                    <x-icon name="x-mark" class="size-4" />
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        @endpersist

        <script>
            window.dispatchEvent(new CustomEvent('alpine:init'));
        </script>
    </body>
</html>
