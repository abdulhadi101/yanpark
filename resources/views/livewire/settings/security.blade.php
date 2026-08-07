<section class="w-full">
    @include('partials.settings-heading')

    <h2 class="sr-only">{{ __('Security settings') }}</h2>

    <x-settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <div>
                <label for="current_password" class="block text-sm font-medium text-zinc-700 mb-1">{{ __('Current password') }}</label>
                <input wire:model="current_password" id="current_password" type="password" required autocomplete="current-password"
                       class="block w-full rounded-lg border border-zinc-300 px-3 py-2.5 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:border-emerald-500 focus:ring-emerald-500" />
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-zinc-700 mb-1">{{ __('New password') }}</label>
                <input wire:model="password" id="password" type="password" required autocomplete="new-password"
                       class="block w-full rounded-lg border border-zinc-300 px-3 py-2.5 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:border-emerald-500 focus:ring-emerald-500" />
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-zinc-700 mb-1">{{ __('Confirm password') }}</label>
                <input wire:model="password_confirmation" id="password_confirmation" type="password" required autocomplete="new-password"
                       class="block w-full rounded-lg border border-zinc-300 px-3 py-2.5 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:border-emerald-500 focus:ring-emerald-500" />
            </div>

            <div class="flex items-center gap-4">
                <button type="submit" data-test="update-password-button"
                        class="rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition-colors cursor-pointer">
                    {{ __('Save') }}
                </button>
            </div>
        </form>
    </x-settings.layout>
</section>
