<?php

namespace App\Livewire\Settings;

use App\Concerns\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Security extends Component
{
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => $this->passwordRules(),
        ]);

        Auth::user()->forceFill([
            'password' => Hash::make($this->password),
        ])->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);

        session()->flash('status', 'password-updated');
    }

    public function render()
    {
        return view('livewire.settings.security');
    }
}
