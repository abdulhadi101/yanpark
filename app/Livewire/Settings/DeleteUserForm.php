<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class DeleteUserForm extends Component
{
    public string $password = '';

    public function deleteUser(): void
    {
        $this->validate([
            'password' => ['required', 'current_password:web'],
        ]);

        auth()->user()->delete();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'), true);
    }

    public function render()
    {
        return view('livewire.settings.delete-user-form');
    }
}
