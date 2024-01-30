<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Livewire\Component;
use App\Models\tblenroled;

class AttachBillingStaffSignatureComponent extends Component
{
    public $scheduleid;
    public $companyid;
    public $is_SignatureAttached;
    public $signed_By;//1 - billing staff , 2 - BOD Manager , 3 - General Manager
    
    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.attach-billing-staff-signature-component');
    }

    public function attachSignature()
    {   

        try 
        {
            if($this->is_SignatureAttached == 0){
                $update_value = 1;
                $update_msg = "Signature attached!";
            }
            else{
                $update_value = 0;
                $update_msg = "Signature removed!";
            }
    
            $update_signature = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->where('tblenroled.scheduleid', $this->scheduleid)
            ->where('tbltraineeaccount.company_id', $this->companyid)
            ->first();

            switch ($this->signed_By) 
            {
                case 1: $update_signature->is_SignatureAttached = $update_value; break;
                case 2: $update_signature->is_Bs_Signed_BOD_Mgr = $update_value; break;
                case 3: $update_signature->is_GmSignatureAttached = $update_value; break;
            }
            
            if(!$update_signature->save()){
                    $this->emit('flashRequestMessage', ['response'=>'error','message'=>'Failed to attach signature!']);
            }
            
            $this->emit('flashRequestMessage', ['response'=>'success','message'=>$update_msg]);
            // Dispatch success event
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Signature Attached'
            ]);
            return redirect()->route('a.billing-viewtrainees');
        } 
        catch (\Exception $e) 
        {
            $this->emit('flashRequestMessage', ['response'=>'error','message'=> $e->getMessage()]);
        }

    }

}
