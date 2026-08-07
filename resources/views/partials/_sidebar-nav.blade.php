@props([
    'heading' => '',
    'links' => [],
    'mobile' => false,
])

<div>
    <p class="px-3 mb-1 text-xs font-semibold text-zinc-500 uppercase tracking-wider">{{ $heading }}</p>
    <div class="space-y-0.5">
        @foreach ($links as $link)
            @php $isActive = request()->routeIs($link['pattern']); @endphp
            <a href="{{ route($link['route']) }}" wire:navigate @if ($mobile) @click="sidebarOpen = false" @endif
               class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors {{ $isActive ? 'text-zinc-900 bg-emerald-50 border border-emerald-200' : 'text-zinc-700 hover:text-zinc-900 hover:bg-zinc-100 border border-transparent' }}">
                <x-icon name="{{ $link['icon'] }}" class="size-5 shrink-0 {{ $isActive ? 'text-emerald-600' : 'text-zinc-500' }}" />
                {{ $link['label'] }}
            </a>
        @endforeach
    </div>
</div>
