<?php

namespace App\Http\Livewire\Admin\Billing\Child\ClientInfo;

use App\Models\tblcompany;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UpdateInfoComponent extends Component
{
    public $companyid;
    public $client_info;
    public $bank_charge;
    protected $rules = [
        'client_info' => 'required',
        'bank_charge' => 'required|numeric'
    ];

    public function mount()
    {
        $this->companyid = Session::get('companyid');
        try 
        {
            $company_data = tblcompany::find($this->companyid);
            if($company_data){
                    $this->client_info = $company_data->billingReceivingInfo;
                    $this->bank_charge = $company_data->bank_charge;
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.billing.child.client-info.update-info-component')->layout('layouts.admin.abase');
    }
    
    public function update()
    {
        $this->validate();
        try 
        {
            $update_clientinfo = tblcompany::find($this->companyid);
            $update_clientinfo->billingReceivingInfo = $this->client_info;
            $update_clientinfo->bank_charge = $this->bank_charge;
            $update = $update_clientinfo->save();
            
            if(!$update){
                    session()->flash('error', 'Info failed to update!');
            }

            session()->flash('success', 'Information saved successfully!');
            return redirect()->route('a.client-info');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }
}
