<div class="flex items-start max-md:flex-col">
    <div class="me-10 w-full pb-4 md:w-[220px]">
        <nav class="space-y-1">
            <a href="{{ route('profile.edit') }}" wire:navigate
               class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('profile.edit') ? 'bg-emerald-50 text-emerald-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                {{ __('Profile') }}
            </a>
            <a href="{{ route('security.edit') }}" wire:navigate
               class="block px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('security.edit') ? 'bg-emerald-50 text-emerald-700' : 'text-zinc-600 hover:text-zinc-900 hover:bg-zinc-100' }}">
                {{ __('Security') }}
            </a>
        </nav>
    </div>

    <hr class="md:hidden border-zinc-200" />

    <div class="flex-1 self-stretch max-md:pt-6">
        <h2 class="text-lg font-semibold text-zinc-900">{{ $heading ?? '' }}</h2>
        <p class="text-sm text-zinc-500">{{ $subheading ?? '' }}</p>

        <div class="mt-5 w-full max-w-lg">
            {{ $slot }}
        </div>
    </div>
</div>
