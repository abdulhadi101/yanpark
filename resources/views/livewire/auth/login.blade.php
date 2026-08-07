<x-layouts::auth :title="__('Log in')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        {{-- @chisel-passkeys --}}
        <x-passkey-verify />
        {{-- @end-chisel-passkeys --}}

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-zinc-700 mb-1">{{ __('Email address') }}</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="email" placeholder="email@example.com"
                       class="block w-full rounded-lg border border-zinc-300 px-3 py-2.5 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:border-emerald-500 focus:ring-emerald-500" />
            </div>

            <!-- Password -->
            <div class="relative">
                <div>
                    <label for="password" class="block text-sm font-medium text-zinc-700 mb-1">{{ __('Password') }}</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password" placeholder="{{ __('Password') }}"
                           class="block w-full rounded-lg border border-zinc-300 px-3 py-2.5 text-sm text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:border-emerald-500 focus:ring-emerald-500" />
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="absolute top-0 text-sm end-0 text-emerald-600 hover:text-emerald-700">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                       class="size-4 rounded border-zinc-300 text-emerald-600 focus:ring-emerald-500" />
                <label for="remember" class="ms-2 text-sm text-zinc-600">{{ __('Remember me') }}</label>
            </div>

            <div class="flex items-center justify-end">
                <button type="submit" data-test="login-button"
                        class="w-full rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-emerald-700 transition-colors cursor-pointer">
                    {{ __('Log in') }}
                </button>
            </div>
        </form>

        {{-- @chisel-registration --}}
        <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-zinc-600">
            <span>{{ __('Don\'t have an account?') }}</span>
            <a href="{{ route('register') }}" wire:navigate class="text-emerald-600 hover:text-emerald-700">{{ __('Sign up') }}</a>
        </div>
        {{-- @end-chisel-registration --}}
    </div>
</x-layouts::auth>
