<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Livewire\Component;
use Illuminate\Support\Facades\Session;

class GenerateBillingComponent extends Component
{
    public $companyid;
    public $scheduleid;
    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.generate-billing-component');
    }

    public function passSessionData()
    {
        Session::put('scheduleid' , $this->scheduleid);
        Session::put('companyid' , $this->companyid);
        // dd($this->companyid);
        return redirect()->route('a.billing-statement');
    }
}
