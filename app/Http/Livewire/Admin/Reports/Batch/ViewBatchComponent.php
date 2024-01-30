<?php

namespace App\Http\Livewire\Admin\Reports\Batch;

use App\Models\tblclntype;
use App\Models\tblcourses;
use App\Models\tblcourseschedule;
use App\Models\tbldorm;
use App\Models\tblenroled;
use App\Models\tblscheduleattendance;
use App\Models\tbltraineeaccount;
use App\Models\tbltrainingreport;
use App\Models\Tper_evaluation_rating;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class ViewBatchComponent extends Component
{
    use WithPagination;
    public $listeners = ['delete_enroled'];
    public $training_id;
    // public $attendance = [];

    public $att_trainees;
    public $pending_trainees;
    public $schedule;
    public $attendanceData;
    public $dateRange = []; // Define dateRange as a class property
    public $attendance = [];
    public $attendanceAbsent = [];
    public $attendanceCancelled = [];
    public $attendanceNoshow = [];
    public $selectedTraineeIds = [];

    public $others = 0;
    public $trid;
    public $ttf = 0;
    public $otherforms = NULL;
    public $sper = 0;
    public $tr = 0;
    public $cas = 0;

    public $idoftrainees;

    public $modaldata = [];
    public $idschedule;
    public $q1a;
    public $q1b;
    public $q2;
    public $q3;
    public $q4;
    public $coursecode;
    public $coursename;
    public $dateoftraining;
    public $instructorname;
    public $trainingcenter = 'd) NYK FIL T.C.';
    public $trainessnameinstr;
    public $arraytraineename;
    public $arraytraineenamewo;
    public $traineesname = [];

    public $checkalreadyaddreport = 0;
    public $trainingreportbtn = 0;
    public $hashdata;

    public $class_number;
    public $practicum_site = "NYK-TDG Maritime Academy";
    public $practicum_date;

    public $array_country = [];
    public $array_principal = [];
    public $array_employer = [];
    public $array_expiry = [];
    public $array_cln = [];
    public $array_control = [];

    // public $array_f_name = [];

    public $f_name, $m_name, $l_name, $suffix, $birthday, $traineeId;

    public $editingTraineeId = null;

    public $selectAll = true; 

    public $packages=[];
    public $room_type;

    public function mount($training_id)
    {
        $this->training_id = $training_id;

        // $this->att_trainees = tblenroled::where('scheduleid', $this->training_id)->where('pendingid', 0)->orWhere('pendingid', 3)
        //     ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
        //     ->orderBy('IsRemedial', 'desc')
        //     ->orderBy('tbltraineeaccount.l_name', 'asc')
        //     ->get();

            $this->att_trainees = tblenroled::where(function ($query) {
                $query->where('scheduleid', $this->training_id)
                    ->where(function ($subquery) {
                        $subquery->where('pendingid', 0)
                            ->orWhere('pendingid', 3);
                    });
            })
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.f_name', 'asc')
            ->get();
        

        $this->pending_trainees = tblenroled::where('scheduleid', $this->training_id)->where('pendingid', 1)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('tbltraineeaccount.l_name', 'asc')
            ->get();

        $this->schedule = tblcourseschedule::find($this->training_id);

        
        $this->packages[1] = $this->schedule->course->atdpackage1;
        $this->packages[2] = $this->schedule->course->atdpackage2;
        $this->packages[3] = $this->schedule->course->atdpackage3;

        $this->class_number = $this->schedule->ClassNumber;
        $this->practicum_date = $this->schedule->PracticumDate;
        $this->practicum_site = Session::get('practicum_site');

        // dd($this->practicum_site);

        $startDate = Carbon::createFromFormat('Y-m-d', $this->schedule->startdateformat);
        $endDate = Carbon::createFromFormat('Y-m-d', $this->schedule->enddateformat);

        while ($startDate <= $endDate) {
            // Check if the current day is not a Sunday (day of week = 0)
            if ($startDate->dayOfWeek !== 0) {
                $this->dateRange[$startDate->format('Y-m-d')] = $startDate->format('l');
            }
            $startDate->addDay();
        }

        $this->room_type = tbldorm::all();

        $this->attendanceData = tblscheduleattendance::whereIn('traineeid', $this->att_trainees->pluck('traineeid'))->where('scheduleid',  $this->schedule->scheduleid)
            ->whereIn('date', array_keys($this->dateRange))
            ->get();

        Session::put('scheduleid', $training_id);

    }

    public function submitAttendance()
    {
        $schedule = tblcourseschedule::find($this->training_id);

        $this->saveAttendance($this->attendance, $schedule, 1);
        $this->saveAttendance($this->attendanceAbsent, $schedule, 4);
        $this->saveAttendance($this->attendanceCancelled, $schedule, 5);
        $this->saveAttendance($this->attendanceNoshow, $schedule, 6);

        // Clear the attendance arrays after submitting
        $this->attendance = [];
        $this->attendanceAbsent = [];
        $this->attendanceCancelled = [];
        $this->attendanceNoshow = [];

        if (Auth::user()->u_type == 1) {
            return redirect()->route('a.view-batch', ['training_id' => $this->training_id]);
        } else {
            return redirect()->route('i.view-batch', ['training_id' => $this->training_id]);
        }
    }

    private function saveAttendance($attendanceData, $schedule, $mode)
    {
        if ($mode > 3) {
            foreach ($attendanceData as $traineeId => $attendanceDates) {
                foreach ($attendanceDates as $date => $attended) {
                    // Save or update the attendance in the database
                    tblscheduleattendance::updateOrCreate([
                        'scheduleid' => $schedule->scheduleid,
                        'traineeid' => $traineeId,
                        'date' => $date,
                        'status' => $mode, // Add the mode here
                    ], [
                        'attended' => $attended, // Assuming 'attended' is the column to indicate attendance status
                    ]);
                }
                $trainee = tblenroled::where('scheduleid', $schedule->scheduleid)->where('traineeid', $traineeId)->first();
                $trainee->attendance_status = 1;
                $trainee->save();
            }
        } else {
            foreach ($attendanceData as $traineeId => $attendanceDates) {
                foreach ($attendanceDates as $date => $attended) {
                    // Save or update the attendance in the database
                    tblscheduleattendance::updateOrCreate([
                        'scheduleid' => $schedule->scheduleid,
                        'traineeid' => $traineeId,
                        'date' => $date,
                        'day' => $mode, // Add the mode here
                    ], [
                        'attended' => $attended, // Assuming 'attended' is the column to indicate attendance status
                    ]);
                    $trainee = tblenroled::where('scheduleid', $schedule->scheduleid)->where('traineeid', $traineeId)->first();
                    $trainee->attendance_status = 0;
                    $trainee->save();
                }
            }
        }
    }

    public function edit($traineeId)
    {
        $this->traineeId = $traineeId;
        $this->editingTraineeId = $traineeId;

        $trainee = tbltraineeaccount::find($traineeId);
        $this->f_name = $trainee->f_name;
        $this->m_name = $trainee->m_name;
        $this->l_name = $trainee->l_name;
        $this->suffix = $trainee->suffix;
        $this->birthday = $trainee->birthday;
    }

    public function save_edit($traineeId)
    {
        $trainee = tbltraineeaccount::find($traineeId);
        $trainee->f_name = $this->f_name;
        $trainee->m_name = $this->m_name;
        $trainee->l_name = $this->l_name;
        $trainee->suffix = $this->suffix;
        $trainee->birthday = $this->birthday;
        $trainee->save();

        $this->editingTraineeId = null;

        $this->log('edit_trainee');
    }

    public function updatedPracticumDate($value)
    {
        $schedule = tblcourseschedule::find($this->training_id);
        $schedule['PracticumDate'] = $this->transformDateRange($value);
        $schedule->save();
        $this->emit('updatedPracticumDate');
    }

    public function updatedPracticumSite($value)
    {
        Session::put('practicum_site', $value);
        $this->emit('updatedPracticumSite');
    }

    public function transformDateRange($dateRange)
    {
        // Split the date range by "to" and trim any spaces
        $dates = array_map('trim', explode(' to ', $dateRange));

        // Parse the dates into Carbon instances
        $startDate = Carbon::parse($dates[0]);
        $endDate = count($dates) > 1 ? Carbon::parse($dates[1]) : null;

        // Format the start date
        $formattedStartDate = $startDate->format('d M Y');

        // Check if it's a date range
        if ($endDate) {
            // Format the end date
            $formattedEndDate = $endDate->format('d M Y');
            // Combine the formatted dates into the final string
            $transformedDateRange = $startDate->format('d') === $endDate->format('d')
                ? $startDate->format('d M Y')
                : $startDate->format('d') . '-' . $endDate->format('d') . ' ' . $startDate->format('M Y');
        } else {
            // Single date format
            $transformedDateRange = $formattedStartDate;
        }

        return $transformedDateRange;
    }

    public function updatedClassNumber($value)
    {
        $schedule = tblcourseschedule::find($this->training_id);

        $schedule['ClassNumber'] = $value;
        $schedule->save();
        $this->emit('updatedClassNumber');
    }

    public function updatedArrayControl($value, $traineeId)
    {

        $schedule = tblcourseschedule::find($this->training_id);
        // Update the database with the new value
        $update_enroled = tblenroled::where('scheduleid', $schedule->scheduleid)->where('traineeid', $traineeId)->first();
        $update_enroled->controlnumber = $value;

        $update_enroled->save();
    }

    // public function updatedArrayFName($value, $traineeId)
    // {
    //     $update_enroled = tbltraineeaccount::find($traineeId);
    //     $update_enroled->f_name = $value;

    //     $update_enroled->save();
    // }

    public function updatedArrayCln($value, $traineeId)
    {

        $schedule = tblcourseschedule::find($this->training_id);
        // Update the database with the new value
        $update_enroled = tblenroled::where('scheduleid', $schedule->scheduleid)->where('traineeid', $traineeId)->first();
        $update_enroled->cln_id = $value;

        $update_enroled->save();
    }

    public function updatedArrayCountry($value, $traineeId)
    {

        $schedule = tblcourseschedule::find($this->training_id);
        // Update the database with the new value
        $update_enroled = tblenroled::where('scheduleid', $schedule->scheduleid)->where('traineeid', $traineeId)->first();
        $update_enroled->pdos_destination = $value;

        $update_enroled->save();
    }

    public function updatedArrayPrincipal($value, $traineeId)
    {

        $schedule = tblcourseschedule::find($this->training_id);
        // Update the database with the new value
        $update_enroled = tblenroled::where('scheduleid', $schedule->scheduleid)->where('traineeid', $traineeId)->first();
        $update_enroled->pdos_principal = $value;

        $update_enroled->save();
    }

    public function updatedArrayEmployer($value, $traineeId)
    {

        $schedule = tblcourseschedule::find($this->training_id);
        // Update the database with the new value
        $update_enroled = tblenroled::where('scheduleid', $schedule->scheduleid)->where('traineeid', $traineeId)->first();
        $update_enroled->pdos_employer = $value;

        $update_enroled->save();
    }

    public function updatedArrayExpiry($value, $traineeId)
    {

        $schedule = tblcourseschedule::find($this->training_id);
        // Update the database with the new value
        $update_enroled = tblenroled::where('scheduleid', $schedule->scheduleid)->where('traineeid', $traineeId)->first();
        $update_enroled->pdos_expiry = $value;

        $update_enroled->save();
    }

   
    public function updatetrainingreport()
    {
        $q1a = $this->q1a;
        $q1b = $this->q1b;
        $q2 = $this->q2;
        $q3 = $this->q3;
        $ttf = $this->ttf;
        $cas = $this->cas;
        $sper = $this->sper;
        $tr = $this->tr;
        $others = $this->others;
        $otherforms = $this->otherforms;

        $toupdate = tbltrainingreport::where('scheduleid', $this->schedule->scheduleid);
        $toupdate->update([
            'Q1_a' => $q1a,
            'Q1_b' => $q1b,
            'Q2' => $q2,
            'Q3' => $q3,
            'isTFF' => $ttf,
            'isCAS' => $cas,
            'isSPER' => $sper,
            'isTR' => $tr,
            'isOthers' => $others,
            'isOtherForms' => $otherforms
        ]);

        $this->arraytraineename = null;

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'center',
            'icon' => 'success',
            'title' => 'Update successfully',
            'confirmbtn' => false
        ]);

        // $this->dispatchBrowserEvent('d_modal',[
        //     'id' => '#trainingreport',
        //     'do' => 'hide'
        // ]);

    }

    public function addtrainingreport()
    {
        $q1a = $this->q1a;
        $q1b = $this->q1b;
        $q2 = $this->q2;
        $q3 = $this->q3;
        $ttf = $this->ttf;
        $cas = $this->cas;
        $sper = $this->sper;
        $tr = $this->tr;
        $others = $this->others;
        $otherforms = $this->otherforms;

        tbltrainingreport::create([
            'scheduleid' => $this->idschedule,
            'Q1_a' => $q1a,
            'Q1_b' => $q1b,
            'Q2' => $q2,
            'Q3' => $q3,
            'isTFF' => $ttf,
            'isCAS' => $cas,
            'isSPER' => $sper,
            'isTR' => $tr,
            'isOthers' => $others,
            'isOtherForms' => $otherforms
        ]);

        $this->arraytraineename = null;

        $this->dispatchBrowserEvent('danielsweetalert', [
            'position' => 'center',
            'icon' => 'success',
            'title' => 'Report Submitted',
            'confirmbtn' => false
        ]);

        // $this->dispatchBrowserEvent('d_modal',[
        //     'id' => '#trainingreport',
        //     'do' => 'hide'
        // ]);

    }

    public function getdatawoinstructor(){
            $this->coursecode = $this->schedule->course->coursecode;
            $this->coursename = strtoupper($this->schedule->course->coursename);
            $this->dateoftraining = strtoupper(date('d F, Y', strtotime($this->schedule->startdateformat))." - ".date('d, F, Y', strtotime($this->schedule->enddateformat)));
            $this->idschedule = $this->schedule->scheduleid;
            
    }


    public function getdata()
    {

        $this->arraytraineenamewo = null;
        $this->arraytraineename = null;
        $this->coursecode = $this->schedule->course->coursecode;
        $this->coursename = strtoupper($this->schedule->course->coursename);
        $this->dateoftraining = strtoupper(date('d F, Y', strtotime($this->schedule->startdateformat)) . " - " . date('d, F, Y', strtotime($this->schedule->enddateformat)));
        $this->instructorname = strtoupper("INSTRUCTOR: " . $this->schedule->instructor->user->formal_name());
        $this->idschedule = $this->schedule->scheduleid;
        if ($this->schedule->assessor) {
            $this->instructorname .= strtoupper(" / ASSESSOR: " . $this->schedule->assessor->user->formal_name());
        }


        $traineesname2 = tblenroled::where('scheduleid', $this->training_id)->where('pendingid', 0)
            ->where('passid', 1)
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->orderBy('tbltraineeaccount.l_name', 'asc')->get();


        $this->idoftrainees = $traineesname2;


        foreach ($traineesname2 as $data) {
            //truncate name
            if (strlen($data->trainee->f_name) >= 15) {
                substr($data->trainee->f_name, 0, 15) . '...';
            }

            $this->arraytraineename .= strtoupper($data->trainee->rank->rankacronym. ' - ' .$data->trainee->formal_name()." \n");
            $this->arraytraineenamewo .= strtoupper($data->trainee->rank->rankacronym. ' - ' .$data->trainee->formal_name().":");
    

        }

            $instructor = $this->schedule->instructor->user->formal_name();
            $scheduleid = $this->schedule->scheduleid;
            $trainingdate = str_replace(',','',$this->dateoftraining);
            $instructorwp = str_replace(',','1',$instructor);
            $arraytraineename = str_replace('\n',':',$this->arraytraineename);
            $arraytraineenamewo = $this->arraytraineenamewo;


            
            // dd($arraytraineenamewo);
    
            if ($this->schedule->assessor?->user?->count() > 0) {
                $assessor = $this->schedule->assessor->user->formal_name();
                $assessorwp = str_replace(',','1',$assessor);
                $this->generatehashdata($trainingdate, $instructorwp, $arraytraineenamewo , $arraytraineename, $assessorwp, $scheduleid);
    
            }else{
                $this->generatehashdatawoassessor($trainingdate, $arraytraineenamewo , $arraytraineename ,$instructorwp, $scheduleid);
            }
    }

    public function checknmccourse()
    {
        $coursecodenmc = tblcourses::where('coursecode', 'LIKE', '%NMC%')->get();
        $courseid = $this->schedule->courseid;


        foreach ($coursecodenmc as $data) {
            if ($data->courseid == $courseid) {
                $this->trainingreportbtn = 1;
            }
        }
    }

    public function log($log){
        $data = [
            'event_type' => $log,
            'user_id' => Auth::user()->user_id ?? Auth::guard('trainee')->user()->traineeid ?? null,
            'trainee_id' => NULL,
            'schedule_id' => $this->schedule->scheduleid,
            'course_id' => $this->schedule->courseid,
        ];

        $this->emitTo(
            'notification.notification-component',
            'add',
            $log,
            $data
        );
    }

    public function generatetrainingreport(){
        $data = [
            'event_type' => 'generate_training_report',
            'user_id' => Auth::user()->user_id ?? Auth::guard('trainee')->user()->traineeid ?? null,
            'trainee_id' => NULL,
            'schedule_id' => $this->schedule->scheduleid,
            'course_id' => $this->schedule->courseid,
        ];

        $this->emitTo(
            'notification.notification-component',
            'add',
            'training-report',
            $data
        );

        $this->redirectRoute('i.training-report');

    }

    public function generatehashdata($trainingdate, $instructorwp, $arraytraineenamewo, $arraytraineename, $assessorwp, $scheduleid)
    {
        session([
            'datatraineereport' => [
                'coursecode' => $this->coursecode,
                'coursename' => $this->coursename,
                'trainingdate' => $trainingdate,
                'instructorwp' => $instructorwp,
                'arraytraineenamewo' => $arraytraineenamewo,
                'arraytraineename' => $arraytraineename,
                'assessorwp' => $assessorwp,
                'scheduleid' => $scheduleid,
                'q1a' => $this->q1a,
                'q1b' => $this->q1b,
                'q2' => $this->q2,
                'q3' => $this->q3,
                'ttf' => $this->ttf,
                'cas' => $this->cas,
                'sper' => $this->sper,
                'tr' => $this->tr,
                'others' => $this->others,
                'otherforms' => $this->otherforms
            ]
        ]);
    }

    public function generatehashdatawoassessor($trainingdate, $arraytraineenamewo,$arraytraineename ,$instructorwp, $scheduleid){
        session([
            'datatraineereport' => [
                'coursecode' => $this->coursecode,
                'coursename' => $this->coursename,
                'trainingdate' => $trainingdate,
                'instructorwp' => $instructorwp,
                'arraytraineenamewo' => $arraytraineenamewo,
                'arraytraineename' => $arraytraineename,
                'scheduleid' => $scheduleid,
                'q1a' => $this->q1a,
                'q1b' => $this->q1b,
                'q2' => $this->q2,
                'q3' => $this->q3,
                'ttf' => $this->ttf,
                'cas' => $this->cas,
                'sper' => $this->sper,
                'tr' => $this->tr,
                'others' => $this->others,
                'otherforms' => $this->otherforms
            ]
        ]);
    }

    public function updatedothers(){
        $trid = $this->trid;
        $others = $this->others;

        if ($others == true) {
            $others = 1;
        }else{
            $others = 0;
        }

        if ($trid != null) {
            $toupdate = tbltrainingreport::find($trid);
            $toupdate->update([
                'isOthers' => $others,
                'isOtherForms' => ""
            ]);
        }

    }

    public function render()
    {
        $this->checknmccourse();

        $checkaddedreport = tbltrainingreport::where('scheduleid', $this->schedule->scheduleid)->get();

        
        if ($checkaddedreport->count() > 0) {
            $this->checkalreadyaddreport = 1;


            foreach ($checkaddedreport as $data) {
                $this->trid = $data->id;
                $this->q1a = $data->Q1_a;
                $this->q1b = $data->Q1_b;
                $this->q2 = $data->Q2;
                $this->q3 = $data->Q3;
                $this->ttf = $data->isTFF;
                $this->cas = $data->isCAS;
                $this->sper = $data->isSPER;
                $this->tr = $data->isTR;
                $this->others = $data->isOthers;
                $this->otherforms = $data->isOtherForms;
            }

            if ($this->schedule->instructor?->user?->formal_name()) {
                $this->getdata();
            }else{
                $this->getdatawoinstructor();
            }

        }else{

            if ($this->schedule->instructor?->user?->formal_name()) {
                $this->getdata();
            }else{
                $this->getdatawoinstructor();
            }

        }

        $trainees = tblenroled::where(function ($query) {
            $query->where('scheduleid', $this->training_id)
                ->where(function ($subquery) {
                    $subquery->where('pendingid', 0)
                        ->orWhere('pendingid', 3);
                });
        })
        ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
        ->orderBy('IsRemedial', 'desc')
        ->orderBy('tbltraineeaccount.f_name', 'asc')
        ->paginate(10);


        
        $cln = tblclntype::all();

        return view('livewire.admin.reports.batch.view-batch-component', [
            'trainees' => $trainees,
            'cln' => $cln,
        ])->layout('layouts.admin.abase');

    }

    public function TPER_form($id)
    {
        // $tper_data = Tper_evaluation_rating::where('enroled_id', '=', $id)->first();

        // if ($tper_data) {
        //     session()->flash('error', 'You already submitted a response for this trainee!');
        // } else {
            Session::put('enroled_id', $id);
            return redirect()->route('i.t_per');
        // }
    }

    public function generateTPER($scheduleid)
    {
        $enroled_data = tblenroled::where('scheduleid', $scheduleid)
            ->whereHas('tper_rating', function ($query) {
            })
            ->get();
        if ($enroled_data->isEmpty()) {
            session()->flash('error', "The instructor has not yet submitted the Trainee's Performance Evaluation Report (TPER) form.");
        } else {
            $this->log('tper_report');
            return redirect()->route('a.tper', ['training_id' => $scheduleid]);
        }
    }

    public function confirmdelete($enroledid){


            $this->dispatchBrowserEvent('confirmation1',[
                'funct' => 'delete_enroled',
                'id' => $enroledid
            ]);
            
        
    }

    public function delete_enroled($enroledid){
            $enroll = tblenroled::find($enroledid); 
            $enroll->delete();

            $data = [
                'event_type' => 'delete_enroled',
                'enroll_id' => $enroll->enroledid,
                'trainee_id' => $enroll->traineeid,
                'schedule_id' => $enroll->scheduleid,
                'course_id' => $enroll->courseid,
            ];

            $this->emitTo('notification.notification-component', 'add',   
            'delete the enrollment', $data);


            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Deleted',
                'confirmbtn' => false
            ]);
        
    }


    public $enroledid,$t_fee_package,$t_fee_price, $total, $selected_dorm;


    public function editPackage($enroledid)
    {
        $enrolled = tblenroled::find($enroledid);
        $this->enroledid = $enrolled->enroledid;
        $this->t_fee_package = $enrolled->t_fee_package;
        $this->t_fee_price = $enrolled->t_fee_price;
        $this->total = $enrolled->total;
        $this->selected_dorm = $enrolled->dormid;
    }

    public function updatedTFeePackage()
    {
        $enrolled = tblenroled::find($this->enroledid);
        $enrolled->t_fee_package = $this->t_fee_package;
        $enrolled->t_fee_price = $this->packages[$this->t_fee_package];
        $enrolled->total = $this->packages[$this->t_fee_package];
        $this->total = $enrolled->total;
        $enrolled->save();
    }

    public function updatedSelectedDorm()
    {
        $enrolled = tblenroled::find($this->enroledid);
        $enrolled->dormid = $this->selected_dorm;
        $enrolled->save();
    }
}
