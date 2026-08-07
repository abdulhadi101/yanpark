@props([
    'sidebar' => false,
    'href' => null,
])

<a href="{{ $href ?? route('dashboard') }}" wire:navigate {{ $attributes->class(['flex items-center gap-2.5']) }}>
    <span class="flex items-center justify-center size-8 rounded-lg bg-zinc-900 text-white shrink-0">
        <x-app-logo-icon class="size-5 fill-current" />
    </span>
    <span class="font-semibold text-zinc-900 text-lg tracking-tight">{{ config('app.name', 'Laravel') }}</span>
</a>
