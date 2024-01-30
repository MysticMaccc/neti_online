<?php

namespace App\Http\Livewire\Company\Enroll;

use App\Models\tblcompanycourse;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CCalendarDetailsComponent extends Component
{
    use WithPagination;

    public $schedule_id;
    public $schedule;
    public $search;

    public $loadtrainee;
    public $selected_schedule;
    public $formatted_registration_num;

    public function mount($schedule_id)
    {
        $this->schedule_id = $schedule_id;

        $this->schedule = tblcourseschedule::where('scheduleid', $schedule_id)->first();
    }

    public function generateRegistrationNumber()
    {
        $registration_num = mt_rand(10000, 99999);
        $year = date('Y');
        $start_month = date('m-d', strtotime($this->schedule->startdateformat));
        $formatted_registration_num = $year . $start_month . '-' . $registration_num;

        $this->formatted_registration_num = $formatted_registration_num;
    }

    public function loadtrainee($id)
    {
        $this->loadtrainee = tbltraineeaccount::where('traineeid', $id)->first();
    }

    private function isEnrolled($trainee_id)
    {
        return tblenroled::where('traineeid', $trainee_id)
            ->where('scheduleid', $this->schedule_id)
            ->exists();
    }

    private function CheckNumberOfTrainees($schedid)
    {
        // Check if the user is enrolled in the course
        return tblenroled::where('scheduleid', $schedid)
            ->where(function ($query) {
                $query->where('tblenroled.pendingid', 0)
                    ->orWhere('tblenroled.pendingid', 1);
            })
            ->where('tblenroled.deletedid', 0)
            ->count();
    }

    public function enroll($trainee_id)
    {
        $selected_schedule = tblcourseschedule::find($this->schedule_id);
        $course_company = tblcompanycourse::where('courseid', $selected_schedule->courseid)->first();
        $isEnrolled = $this->isEnrolled($trainee_id);
        $pending_enrolled_trainees = $this->CheckNumberOfTrainees($this->schedule_id);


        if ($isEnrolled) {

            $this->dispatchBrowserEvent('error-log', [
                'title' => 'The trainee are already enrolled in this course.'
            ]);

            return;
        }
        

        
        if ($pending_enrolled_trainees >= $selected_schedule->course->maximumtrainees) {

            $this->dispatchBrowserEvent('error-log', [
                'title' => 'This training schedule are reached the maximum trainees. Please select another.'
            ]);
            
            return;
        }
        
        $this->generateRegistrationNumber();

        $new_enrol = new tblenroled();
        $new_enrol->registrationcode = $this->formatted_registration_num;

        $new_enrol->scheduleid = $this->schedule_id;
        $new_enrol->courseid =  $selected_schedule->courseid;
        $new_enrol->traineeid = $trainee_id;

        $new_enrol->t_fee_package =  $course_company->t_fee_package;

        if ($course_company->t_fee_package == 2 || $course_company->t_fee_package == 3){
            $new_enrol->busid = 1;
        } else {
            $new_enrol->busid = 0;
        }
        $new_enrol->paymentmodeid = 1;

        //transactions
        $new_enrol->t_fee_price = $course_company->t_fee_price;
        $new_enrol->meal_price = $course_company->meal_price;
        $new_enrol->dorm_price = $course_company->dorm_price;

        //calculate the total price
        $total = $course_company->t_fee_price + $course_company->meal_price + $course_company->dorm_price;
        $new_enrol->total =  $total;

        $new_enrol->fleetid = $this->loadtrainee->fleet_id;

        $selected_schedule->numberofenroled += 1;

        $new_enrol->save();
        $selected_schedule->save();
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes
    }

    public function render()
    {
        $trainees = tbltraineeaccount::where('company_id', Auth::user()->company_id)->where('l_name', 'LIKE', '%' . $this->search . '%')->paginate(10);
        $e_trainees = tblenroled::where('scheduleid', $this->schedule_id)->get();

        return view(
            'livewire.company.enroll.c-calendar-details-component',
            [
                'trainees' => $trainees,
                'e_trainees' => $e_trainees,
            ]
        )->layout('layouts.admin.abase');
    }
}
