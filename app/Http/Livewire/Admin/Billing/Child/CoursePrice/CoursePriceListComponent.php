<?php

namespace App\Http\Livewire\Admin\Billing\Child\CoursePrice;

use App\Models\tblcompanycourse;
use Livewire\Component;

class CoursePriceListComponent extends Component
{
    public $course;
    public $isEdit = null;
    protected $rules = [
        'course.ratepeso' => 'required|numeric',
        'course.rateusd' => 'required|numeric'
    ];

    public function render()
    {
        return view('livewire.admin.billing.child.course-price.course-price-list-component');
    }

    public function edit($id)
    {
        $this->isEdit = $id;
    }

    public function closeEdit()
    {
        $this->isEdit = null;
    }

    public function update($id)
    {
        $this->validate();
        try {
            $update_price = tblcompanycourse::find($id);
            if ($update_price) {
                $update_price->ratepeso = $this->course->ratepeso;
                $update_price->rateusd = $this->course->rateusd;
                if (!$update_price->save()) {
                    $this->emit('getRequestMessage', ['response' => 'error', 'message' => 'Failed to update price!']);
                }

                $this->emit('getRequestMessage', ['response' => 'success', 'message' => 'Price updated successfully!']);
                $this->isEdit = null;
            }else{
                $this->emit('getRequestMessage', ['response' => 'error', 'message' => 'Price data not found!']);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $delete = tblcompanycourse::find($id)->delete();
            if(!$delete){
                $this->emit('getRequestMessage', ['response' => 'error', 'message' => 'Failed to delete price!']);
            }
            $this->emit('getRequestMessage', ['response' => 'success', 'message' => 'Price deleted successfully!']);

        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
}
