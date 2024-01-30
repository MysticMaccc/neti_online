<?php

namespace App\Http\Livewire\Admin\Reports;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class AReportsDashboardComponent extends Component
{
    use AuthorizesRequests;

    public function mount()
    {
            Gate::authorize('authorizeAdminComponents', 14);
    }

    public function render()
    {
        return view('livewire.admin.reports.a-reports-dashboard-component')->layout('layouts.admin.abase');
    }
}
