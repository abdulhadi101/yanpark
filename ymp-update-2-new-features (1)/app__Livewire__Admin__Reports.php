<?php
// File path: app/Livewire/Admin/Reports.php

namespace App\Livewire\Admin;

use App\Services\ReportService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Reports extends Component
{
    public string $period = 'daily'; // daily | weekly | monthly

    public function render(ReportService $reports)
    {
        return view('livewire.admin.reports', [
            'summary' => $reports->periodSummary($this->period),
        ]);
    }
}
