<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class StartBillingComponent extends Component
{
    public $companyid;
    public $scheduleid;
    public $billingstatusid;

    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.start-billing-component');
    }

    public function Start($statusId)
    {
        try {
            $update = DB::table('tblenroled as a')
                ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid')
                ->where('c.company_id', '=', $this->companyid)
                ->where('b.scheduleid', '=', $this->scheduleid)
                ->update(['a.billingstatusid' => $statusId]);
            if(!$update){
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Failed to start process'
                ]);
            }


            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Process Started!'
            ]);
            Session::put('billingstatusid', $statusId);
            Session::put('scheduleid', $this->scheduleid);
            Session::put('companyid', $this->companyid);
            return redirect()->route('a.billing-viewtrainees');
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
}
