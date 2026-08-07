<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Profile settings') }}</h2>

    <x-settings.layout :heading="__('Profile')" :subheading="__('Update your name and email address')">
        <form wire:submit="updateProfile" class="my-6 w-full space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-zinc-700 mb-1">{{ __('Name') }}</label>
                <input wire:model="name" id="name" type="text" required autofocus autocomplete="name"
                       class="block w-full rounded-lg border border-zinc-300 px-3 py-2.5 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:border-emerald-500 focus:ring-emerald-500" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 mb-1">{{ __('Email') }}</label>
                <input wire:model="email" id="email" type="email" required autocomplete="email"
                       class="block w-full rounded-lg border border-zinc-300 px-3 py-2.5 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:border-emerald-500 focus:ring-emerald-500" />

                {{-- @chisel-email-verification --}}
                @if ($this->hasUnverifiedEmail)
                    <div>
                        <p class="mt-4 text-sm text-zinc-500">
                            {{ __('Your email address is unverified.') }}

                            <a class="text-sm cursor-pointer text-emerald-600 hover:text-emerald-700" wire:click.prevent="resendVerificationNotification">
                                {{ __('Click here to re-send the verification email.') }}
                            </a>
                        </p>
                    </div>
                @endif
                {{-- @end-chisel-email-verification --}}
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                        class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition-colors cursor-pointer">
                    {{ __('Save') }}
                </button>
            </div>
        </form>

        {{-- @chisel-email-verification --}}
        @if ($this->showDeleteUser)
        {{-- @end-chisel-email-verification --}}
            <livewire:settings.delete-user-form />
        {{-- @chisel-email-verification --}}
        @endif
        {{-- @end-chisel-email-verification --}}
    </x-settings.layout>
</section>
