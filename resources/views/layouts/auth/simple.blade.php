<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white antialiased">
        <div class="bg-background flex min-h-svh flex-col items-center justify-center gap-6 p-6 md:p-10">
            <div class="flex w-full max-w-sm flex-col gap-2">
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-2 font-medium" wire:navigate>
                    <span class="flex h-9 w-9 mb-1 items-center justify-center rounded-md">
                        <x-app-logo-icon class="size-9 fill-current text-emerald-600" />
                    </span>
                    <span class="sr-only">{{ config('app.name', 'Laravel') }}</span>
                </a>
                <div class="flex flex-col gap-6">
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
