<?php

namespace App\Livewire\Settings;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Appearance extends Component
{
    public string $theme = 'light';

    public function mount(): void
    {
        $this->theme = session()->get('theme', 'light');
    }

    public function updatedTheme(string $value): void
    {
        session()->put('theme', $value);
        $this->dispatch('theme-updated', theme: $value);
    }

    public function render()
    {
        return view('livewire.settings.appearance');
    }
}
