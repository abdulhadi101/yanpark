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
    }
}
