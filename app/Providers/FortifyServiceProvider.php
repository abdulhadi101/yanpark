<?php

namespace App\Providers;

use Laravel\Fortify\Fortify;
use Laravel\Fortify\FortifyServiceProvider as BaseServiceProvider;

class FortifyServiceProvider extends BaseServiceProvider
{
    public function boot(): void
    {
        Fortify::createUsersUsing(\App\Actions\Fortify\CreateNewUser::class);
        Fortify::resetUserPasswordsUsing(\App\Actions\Fortify\ResetUserPassword::class);

        Fortify::loginView(fn () => view('livewire.auth.login'));
        Fortify::registerView(fn () => view('livewire.auth.register'));
        Fortify::requestPasswordResetLinkView(fn () => view('livewire.auth.forgot-password'));
        Fortify::resetPasswordView(fn () => view('livewire.auth.reset-password'));
        Fortify::verifyEmailView(fn () => view('livewire.auth.verify-email'));
        Fortify::confirmPasswordView(fn () => view('livewire.auth.confirm-password'));
        Fortify::twoFactorChallengeView(fn () => view('livewire.auth.two-factor-challenge'));
    }
}