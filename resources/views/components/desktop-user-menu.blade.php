@props(['href' => '#'])

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-zinc-700 hover:text-zinc-900 hover:bg-zinc-100 transition-colors cursor-pointer">
        <span class="flex items-center justify-center size-8 rounded-full bg-zinc-200 text-zinc-600 text-sm font-semibold shrink-0">
            {{ auth()->user()->initials() }}
        </span>
        <span class="hidden lg:flex lg:flex-col lg:text-start">
            <span class="font-medium text-zinc-900 truncate">{{ auth()->user()->name }}</span>
            <span class="text-zinc-500 truncate text-xs">{{ auth()->user()->email }}</span>
        </span>
        <x-icon name="chevrons-up-down" class="size-4 shrink-0 text-zinc-400" />
    </button>

    <div x-show="open" @click.outside="open = false" x-transition.origin.top-right x-cloak
         class="absolute right-0 top-full mt-2 w-56 bg-white rounded-xl shadow-lg border border-zinc-200 py-1 z-50">
        <div class="flex items-center gap-2 px-4 py-3 text-sm">
            <span class="flex items-center justify-center size-8 rounded-full bg-zinc-200 text-zinc-600 text-sm font-semibold shrink-0">
                {{ auth()->user()->initials() }}
            </span>
            <div class="grid flex-1 text-sm leading-tight">
                <span class="font-medium text-zinc-900 truncate">{{ auth()->user()->name }}</span>
                <span class="text-zinc-500 truncate">{{ auth()->user()->email }}</span>
            </div>
        </div>
        <div class="border-t border-zinc-100 my-1"></div>
        <a href="{{ route('profile.edit') }}" wire:navigate class="flex items-center gap-2 px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50">
            <x-icon name="cog" class="size-4 shrink-0" />
            {{ __('Settings') }}
        </a>
        <div class="border-t border-zinc-100 my-1"></div>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-zinc-700 hover:bg-zinc-50 cursor-pointer" data-test="logout-button">
                <x-icon name="arrow-right-start-on-rectangle" class="size-4 shrink-0" />
                {{ __('Log out') }}
            </button>
        </form>
    </div>
</div>
