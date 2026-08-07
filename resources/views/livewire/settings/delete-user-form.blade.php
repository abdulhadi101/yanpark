<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <h2 class="text-lg font-semibold text-zinc-900">{{ __('Delete account') }}</h2>
        <p class="text-sm text-zinc-500">{{ __('Delete your account and all of its resources') }}</p>
    </div>

    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors cursor-pointer">
        {{ __('Delete account') }}
    </button>

    <div x-data="{ open: @js($errors->isNotEmpty()) }" x-on:open-modal.window="if ($event.detail === 'confirm-user-deletion') open = true" x-on:close-modal.window="if ($event.detail === 'confirm-user-deletion') open = false"
         x-show="open" x-cloak class="relative z-50" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-zinc-900/50" x-on:click="open = false"></div>
        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="w-full max-w-lg rounded-xl bg-white shadow-lg" x-on:click.outside="open = false" x-transition>
                    <form method="POST" wire:submit="deleteUser" class="space-y-6 p-6">
                        <div>
                            <h3 class="text-lg font-semibold text-zinc-900">{{ __('Are you sure you want to delete your account?') }}</h3>
                            <p class="mt-2 text-sm text-zinc-500">
                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                            </p>
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-zinc-700 mb-1">{{ __('Password') }}</label>
                            <input wire:model="password" id="password" type="password"
                                   class="block w-full rounded-lg border border-zinc-300 px-3 py-2.5 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:border-emerald-500 focus:ring-emerald-500" />
                        </div>

                        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                            <button type="button" x-on:click="open = false"
                                    class="rounded-lg border border-zinc-300 px-4 py-2 text-sm font-medium text-zinc-700 hover:bg-zinc-50 transition-colors cursor-pointer">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit"
                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition-colors cursor-pointer">
                                {{ __('Delete account') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
