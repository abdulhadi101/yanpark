<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased">
        <div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
            <div class="bg-zinc-100 relative hidden h-full flex-col p-10 text-zinc-800 lg:flex lg:border-e lg:border-zinc-200">
                <div class="absolute inset-0 bg-neutral-100"></div>
                <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-medium" wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-md">
                        <x-app-logo-icon class="me-2 h-7 fill-current text-emerald-600" />
                    </span>
                    {{ config('app.name', 'Laravel') }}
                </a>

                @php
                    [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
                @endphp

                <div class="relative z-20 mt-auto">
                    <blockquote class="space-y-2">
                        <p class="text-lg font-medium">&ldquo;{{ trim($message) }}&rdquo;</p>
                        <footer class="text-sm">{{ trim($author) }}</footer>
                    </blockquote>
                </div>
            </div>
            <div class="w-full lg:p-8">
                <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
                    <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden" wire:navigate>
                        <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-emerald-600" />
                        </span>

                        <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                    </a>
                    {{ $slot }}
                </div>
            </div>
        </div>

        @persist('toast')
            <div x-data="{ toasts: [], show(msg, type = 'success') { const id = Date.now(); this.toasts.push({ id, msg, type }); setTimeout(() => this.dismiss(id), 5000) }, dismiss(id) { this.toasts = this.toasts.filter(t => t.id !== id) } }"
                 x-on:toast.window="show($event.detail.msg, $event.detail.type)"
                 class="fixed inset-x-0 bottom-0 z-50 flex items-end justify-end p-4 pointer-events-none sm:items sm:p-6">
                <div class="flex w-full flex-col items-end gap-2">
                    <template x-for="toast in toasts" :key="toast.id">
                        <div x-show="true" x-transition class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black/5">
                            <div class="p-4 flex items-center gap-3">
                                <x-icon name="check-circle" class="size-5 text-emerald-500 shrink-0" />
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
    </body>
</html>
