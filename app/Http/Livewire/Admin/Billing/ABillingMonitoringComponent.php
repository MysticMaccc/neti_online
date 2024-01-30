<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tbltraineeaccount;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ABillingMonitoringComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;

    public function mount()
    {
        // Gate::authorize('authorizeAdminComponents', 11);
        Gate::authorize('authorizeBillingModule', 11);
    }
    
    public function render()
    {
        return view('livewire.admin.billing.a-billing-monitoring-component')->layout('layouts.admin.abase');
    }



}
