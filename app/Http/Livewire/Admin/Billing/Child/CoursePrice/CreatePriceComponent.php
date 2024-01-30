<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use App\Models\tblcompany;
use App\Models\tblcompanycourse;
use App\Models\tblcourses;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CreatePriceComponent extends Component
{
    public $companyid;
    public $ratepeso;
    public $rateusd;
    public $selectedcourse;
    protected $rules = [
            'selectedcourse' => 'required',
            'ratepeso' => 'required|numeric',
            'rateusd' => 'required|numeric',
    ];
    
    public function mount()
    {
            $this->companyid = Session::get('companyid');
    }

    public function render()
    {
        $courses_list = tblcourses::where('deletedid', 0)
        ->orderBy('coursecode', 'asc')
        ->get();

        return view('livewire.admin.billing.child.course-price.create-price-component', compact('courses_list'));
    }

    public function store()
    {
        $this->validate();
        try 
        {
            $store = tblcompanycourse::create([
                    'companyid' => $this->companyid,
                    'courseid' => $this->selectedcourse,
                    'ratepeso' => $this->ratepeso,
                    'rateusd' => $this->rateusd
            ]);
            if(!$store){
                    $this->emit('getRequestMessage', ['response' => 'error', 'message' => 'Saving price failed!']);
                    $this->closeModal();
            }

            $this->emit('getRequestMessage', ['response' => 'success', 'message' => 'Price saved successfully!']);
            $this->closeModal();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function closeModal()
    {
        $this->dispatchBrowserEvent('d_modal', [
            'id' => '#addmodal',
            'do' => 'hide'
        ]);
    }
}
