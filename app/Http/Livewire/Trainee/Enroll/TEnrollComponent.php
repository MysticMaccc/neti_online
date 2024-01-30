<?php

namespace App\Http\Livewire\Trainee\Enroll;

use App\Mail\SendBillingAccountEmail;
use App\Models\tblatdmealprice;
use App\Models\tblbillingaccount;
use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tbldorm;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class TEnrollComponent extends Component
{
    public $course_id;
    public $course_main;
    public $course_sched;
    public $c_title;
    public $selectedSched;
    public $selectedButtonIndex = null;
    public $t_fee;
    public $selectedPackage;
    public $bus_id;
    public $alert_notif;
    public $disabled;

    public $start_date;
    public $end_date;
    public $room_start;
    public $room_end;

    public $payment_features;
    public $dorm_name;
    public $dorm_price = null;
    public $duration;
    public $total_price_dorm = 0;
    public $meal_price = 0;
    public $total = 0;
    public $totalmealdorm = 0;
    public $total_meal = 0;
    public $selectedDorm = null;

    public $formatted_registration_num;

    public $showAdditionalForm = false;

    public $trainee;
    public $selected_schedule;

    public $dateonlinefrom;
    public $dateonlineto;
    public $dateonsitefrom;
    public $dateonsiteto;

    public $acceptTerms;

    public function mount($course_id)
    {
        $this->course_id = $course_id;
        $this->course_main = tblcourses::find($course_id);
        $this->trainee = Auth::guard('trainee')->user();
        $this->t_fee =  $this->course_main->trainingfee;
        // Get the current year and current date
        $currentDate = Carbon::now();

        // Fetch the training schedules for the current year and onwards
        $this->course_sched = tblcourseschedule::where('courseid', $course_id)->where('cutoffid', 0)->where('specialclassid', 0)
            ->whereDate('startdateformat', '>=', $currentDate)
            ->get();

        $this->c_title = $this->course_main->coursename;
    }

    protected $listeners = ['eventClicked'];

    public function eventClicked($schedid)
    {
        // Verify if the user is already enrolled in the course
        $isEnrolled = $this->isEnrolled($schedid);
        $pending_enrolled_trainees = $this->CheckNumberOfTrainees($schedid);
        $schedule = tblcourseschedule::find($schedid);

        if ($isEnrolled) {

            $this->dispatchBrowserEvent('error-log', [
                'title' => 'You are already enrolled in this course.'
            ]);
            // Continue with handling the event click
            $this->selectedSched = '';
            return;
        }

        if ($pending_enrolled_trainees >= $schedule->course->maximumtrainees) {

            $this->dispatchBrowserEvent('error-log', [
                'title' => 'This training schedule are reached the maximum trainees. Please select another.'
            ]);
            // Continue with handling the event click
            $this->selectedSched = '';
            return;
        }

        $schedule = tblcourseschedule::find($schedid);
        $this->start_date = $schedule->startdateformat;
        $this->end_date = $schedule->enddateformat;
        $this->selectedSched = $schedule ? $schedule->scheduleid : null;
        $this->dateonlinefrom = $schedule->dateonlinefrom;
        $this->dateonlineto = $schedule->dateonlineto;
        $this->dateonsitefrom = $schedule->dateonsitefrom;
        $this->dateonsiteto = $schedule->dateonsiteto;

        $this->dispatchBrowserEvent('livewire:load');
    }


    private function isEnrolled($schedid)
    {
        $user = Auth::guard('trainee')->user();

        // Check if the user is enrolled in the course
        return tblenroled::where('traineeid', $user->traineeid)
            ->where('scheduleid', $schedid)
            ->where('deletedid', 0)
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

    public function toggleAdditionalForm()
    {
        $this->showAdditionalForm = !$this->showAdditionalForm;
    }

    public function generateRegistrationNumber()
    {
        $registration_num = mt_rand(10000, 99999);
        $year = date('Y');
        $start_month = date('m-d', strtotime($this->start_date));
        $formatted_registration_num = $year . $start_month . '-' . $registration_num;

        $this->formatted_registration_num = $formatted_registration_num;
    }

    public function create()
    {
        $this->validate([
            'selectedSched' => 'required',
            'payment_features' => 'required',
        ]);

        $this->selected_schedule = tblcourseschedule::find($this->selectedSched);
        $this->generateRegistrationNumber();

        $new_enrol = new tblenroled();
        $new_enrol->registrationcode = $this->formatted_registration_num;

        $new_enrol->scheduleid = $this->selectedSched;
        $new_enrol->courseid = $this->course_id;
        $new_enrol->traineeid = $this->trainee->traineeid;

        // $new_enrol->busmodeid = $this->bus_mode;
        $new_enrol->paymentmodeid = $this->payment_features;
        $new_enrol->dormid = $this->selectedDorm;
        $new_enrol->fleetid = $this->trainee->fleet_id;

        if ($this->showAdditionalForm == true) {
            $new_enrol->checkindate = $this->room_start;
            $new_enrol->checkoutdate = $this->room_end;

            //getting duration of date
            $checkin = Carbon::parse($this->room_start);
            $checkout = Carbon::parse($this->room_end);
            $new_enrol->duration = $checkin->diffInDays($checkout) + 1;

            $dorm_price = tbldorm::find($this->selectedDorm);

            // Check if $dorm_price is not null before accessing its properties
            if ($dorm_price) {
                //total price for dorm
                $total_price_dorm = $dorm_price->atddormprice * ($checkin->diffInDays($checkout) + 1);
                $new_enrol->dorm_price = $total_price_dorm;
            }

            $total_meal =  tblatdmealprice::find(1)->atdmealprice * ($checkin->diffInDays($checkout) + 1);
            //gett the meal price
            $new_enrol->meal_price = $total_meal;
        }

        if ($this->bus_id) {
            $new_enrol->busid = 1;
            $new_enrol->busmodeid = $this->bus_id;
        }

        $new_enrol->t_fee_price = $this->t_fee;
        $new_enrol->t_fee_package = $this->selectedPackage;

        $this->selected_schedule->numberofenroled += 1;

        //calculate the total price
        $total = $this->total_price_dorm + $this->t_fee + $this->meal_price;
        $new_enrol->total = $total;

        $dateconfirmed = Carbon::now('Asia/Manila');        
        $new_enrol->dateconfirmed = $dateconfirmed;
        $new_enrol->enrolledby = Auth::guard('trainee')->user()->formal_name();
        $new_enrol->save();
        $this->selected_schedule->save();

        $latestId = $new_enrol->enroledid;
        Session::put('latest_enrol_id', $latestId);

        $enrol = tblenroled::where('enroledid', $latestId)->first();
        $billing = tblbillingaccount::where('is_active', 1)->first();

        if($this->payment_features == 2){
        Mail::to($this->trainee->email)->send(new SendBillingAccountEmail($this->trainee, $billing, $enrol));
        }
        return redirect()->to('processing-enrol');
    }


    public function render()
    {
        $user = Auth::guard('trainee')->user();
        $dorm = tbldorm::all();

        if ($this->start_date && $this->bus_id == 2 && $this->course_main->type->coursetypeid != 1) {
            $this->t_fee =  $this->course_main->atdpackage3;
            $this->selectedPackage = 3;
        } elseif ($this->start_date && $this->bus_id == 1 && $this->course_main->type->coursetypeid != 1) {
            $this->t_fee =  $this->course_main->atdpackage2;
            $this->selectedPackage = 2;
        } elseif ($this->start_date && $this->bus_id == 1 && $this->course_main->type->coursetypeid == 1) {
            $this->t_fee =  $this->course_main->atdpackage2;
            $this->selectedPackage = 2;
        } elseif ($this->start_date) {
            $this->t_fee =  $this->course_main->atdpackage1;
            $this->selectedPackage = 1;
        } else {
            $this->t_fee = 0;
        }



        if($this->showAdditionalForm){
            if ($this->selectedDorm) {
                $checkin = Carbon::parse($this->room_start);
                $checkout = Carbon::parse($this->room_end);
                $this->duration = $checkin->diffInDays($checkout) + 1;
                $this->dorm_price = tbldorm::find($this->selectedDorm);
                //total price for dorm
                if($this->room_start && $this->room_end){
                    $this->dorm_name = tbldorm::find($this->selectedDorm);
                    $this->total_price_dorm = $this->dorm_price->atddormprice * $this->duration;
                    //gett the meal price
                    $this->meal_price = tblatdmealprice::find(1)->atdmealprice * $this->duration;
                }
                
            }
        }else {
            $this->meal_price = 0;
            $this->total_price_dorm = 0;
            $this->selectedDorm = null;
        }

        $this->totalmealdorm = $this->meal_price + $this->total_price_dorm;
        $this->total = $this->totalmealdorm + $this->t_fee;


        return view(
            'livewire.trainee.enroll.t-enroll-component',
            [
                'user' => $user,
                'dorm' => $dorm,
            ]
        )->layout('layouts.trainee.tbase');
    }
}
