<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout(): void
    {
        Auth::logout();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'), true);
    }
}
