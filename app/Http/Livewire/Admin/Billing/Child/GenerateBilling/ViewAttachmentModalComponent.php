<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Livewire\Component;
use App\Models\billingattachment;

class ViewAttachmentModalComponent extends Component
{
    public $scheduleid;
    public $companyid;
    public function render()
    {
        try 
        {
            $attachments = billingattachment::where('scheduleid' , $this->scheduleid)
                                 ->where('companyid' , $this->companyid)
                                 ->where('is_deleted' , 0)
                                 ->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        return view('livewire.admin.billing.child.generate-billing.view-attachment-modal-component', compact('attachments'));
    }

}
