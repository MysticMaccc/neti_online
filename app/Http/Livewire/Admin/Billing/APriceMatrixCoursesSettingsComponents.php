<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblcompany;
use App\Models\tblcompanycourse;
use App\Models\tblcourses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class APriceMatrixCoursesSettingsComponents extends Component
{
    use ConsoleLog;
    public $companyid;
    public $company_name;
    public $selectedcourse;
    protected $listeners = ['getRequestMessage' => 'flashRequestMessage'];

    public function mount()
    {
        try 
        {
            $this->companyid = Session::get('companyid');

            $company_data = tblcompany::find($this->companyid);
            if($company_data){
                    $this->company_name = $company_data->company;
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function flashRequestMessage($data)
    {
            session()->flash($data['response'],$data['message']);
    }

    
    
    public function render()
    {
        try 
        {
            $courses = tblcompanycourse::where('companyid',$this->companyid)
                                        ->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.billing.a-price-matrix-courses-settings-components' , 
        [
            'courses' => $courses
        ])->layout('layouts.admin.abase');
    }
}
