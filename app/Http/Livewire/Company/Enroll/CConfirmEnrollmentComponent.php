<?php

namespace App\Http\Livewire\Company\Enroll;

use App\Models\tblbillingdrop;
use App\Models\tblcourses;
use App\Models\tblenroled;
use App\Models\tbltraineeaccount;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class CConfirmEnrollmentComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_status;
    public $search;
    public $selected_course;
    public $selected_stat = null;
    public $selected_batch;
    public $selectedItems = [];
    public $reason;
    public $enroledid;

    public $listeners = ['delete_enroled', 'drop'];

    
    public function approved_enroll($id)
    {
        $enroll = tblenroled::find($id);
        $enroll->pendingid = 0;
        $dateconfirmed = Carbon::now('Asia/Manila');
        $enroll->dateconfirmed = $dateconfirmed;
        $enroll->enrolledby = Auth::user()->formal_name();
        $enroll->save();
        // $this->generatePdf($id);
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Approved enrollment application'
        ]);

        $data = [
            'event_type' => 'enroll_approved',
            'enroll_id' => $enroll->enroledid,
            'trainee_id' => $enroll->traineeid,
            'schedule_id' => $enroll->scheduleid,
            'course_id' => $enroll->courseid,
        ];

        $this->emitTo('notification.notification-component', 'add',   
        'approved an enrollment application', $data);

    }

    
    public function selectItem($itemId)
    {
        if (in_array($itemId, $this->selectedItems)) {
            $this->selectedItems = array_diff($this->selectedItems, [$itemId]);
        } else {
            $this->selectedItems[] = $itemId;
        }
    }

    
    public function performAction()
    {
        foreach($this->selectedItems as $item){
            $enroled_data = tblenroled::find($item);
            $enroled_data->pendingid = 0;
            $enroled_data->save();
            // $this->generatePdf($enroled_data->enroledid);
        }
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Approved enrollment application'
        ]);
        $this->selectedItems = [];
    }



    // public function generatePdf($enrol_id)
    // {
    //     $enrol = tblenroled::findOrFail($enrol_id);
    //     $data = [
    //         'enrol' => $enrol,
    //     ];
    //     $pdf = Pdf::loadView('livewire.admin.generate-docs.a-generate-admission-slip', $data);
    //     $pdf->setPaper('a4', 'landscape');
    //     Mail::to($enrol->trainee->email)->send(new SendAdmissionSlip($pdf));
    // }

    public function reject_enroll($id)
    {
        $enroll = tblenroled::find($id);
        $enroll->deletedid = 1;
        $enroll->save();

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Reject enrollment application'
        ]);


        $data = [
            'event_type' => 'enroll_rejected',
            'enroll_id' => $enroll->enroledid,
            'trainee_id' => $enroll->traineeid,
            'schedule_id' => $enroll->scheduleid,
            'course_id' => $enroll->courseid,
        ];

        $this->emitTo('notification.notification-component', 'add',   
        'rejected an enrollment application', $data);
    }

    public function load_status($id)
    {
        $status = tblenroled::find($id);
        $this->selected_status = $status->pendingid;
    }

    public function generateAS($enroll_id)
    {
        Session::put('enroll_id', $enroll_id);
        return redirect()->route('a.viewadmission');
    }

    public function confirmdelete($enroledid){
        $status = tblenroled::find($enroledid);


        $this->dispatchBrowserEvent('confirmation1',[
            'funct' => 'delete_enroled',
            'id' => $enroledid
        ]);
        

        $data = [
            'event_type' => 'enroll_changed_status',
            'enroll_id' => $status->enroledid,
            'trainee_id' => $status->traineeid,
            'schedule_id' => $status->scheduleid,
            'course_id' => $status->courseid,
        ];

        $this->emitTo('notification.notification-component', 'add',   
        'changed status of enrollment application', $data);
    }

    public function delete_enroled($enroledid){
        $enroll = tblenroled::find($enroledid); 
        $enroll->deletedid = 1;
        $enroll->save();

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Deleted',
            'confirmbtn' => false
        ]);
    }

    public function confirmdrop($enroledid){
        $enrol = tblenroled::find($enroledid);
        $this->enroledid = $enrol->enroledid;
    }

    public function drop(){
        $enroll = tblenroled::find($this->enroledid); 
        $enroll->pendingid = 2;
        $enroll->dropid = 1;    
        $datedrop = Carbon::now('Asia/Manila');
        $enroll->datedrop = $datedrop;
        $enroll->save();

        $course = tblcourses::find($enroll->courseid);

        $billdrop = new tblbillingdrop();
        $billdrop->enroledid = $this->enroledid;

        $billdrop->courseid = $enroll->courseid;
        $billdrop->coursename = $course->coursename;
        $billdrop->price = $enroll->t_fee_price;

        $billdrop->dateconfirmed = $enroll->dateconfirmed;
        $billdrop->datedrop =  $datedrop;
        $billdrop->reason = $this->reason;
        $billdrop->droppedby = Auth::user()->formal_name();
        $billdrop->save();


        $data = [
            'event_type' => 'drop_status',
            'enroll_id' => $enroll->enroledid,
            'trainee_id' => $enroll->traineeid,
            'schedule_id' => $enroll->scheduleid,
            'course_id' => $enroll->courseid,
        ];

        $this->emitTo('notification.notification-component', 'add',   
        'changed status "DROP" of enrollment application', $data);

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Successfully dropped',
            'confirmbtn' => false
        ]);
        
    }

    public function render()
    {
        $query = tblenroled::query()->where('deletedid', 0)
        ->with(['trainee' => function ($query) {
            $query->with('company');
        }])
        ->whereHas('trainee', function ($query) {
            $query->where('company_id', Auth::user()->company_id);
        });

        if (!is_null($this->selected_stat)) {
            $query->where('pendingid', $this->selected_stat);
        }

        $searchTerm = '%' . $this->search . '%';
        $query->where(function ($q) use ($searchTerm) {
            $q->where(function ($q) use ($searchTerm) {
                $q->where('registrationnumber', 'like', $searchTerm);
            });

            $q->orWhereHas('trainee', function ($q) use ($searchTerm) {
                $q->where(function ($q) use ($searchTerm) {
                    $q->where('f_name', 'like', $searchTerm)
                        ->orWhere('m_name', 'like', $searchTerm)
                        ->orWhere('l_name', 'like', $searchTerm);
                });
            });
        });


        $all_enroll = $query->orderBy('enroledid', 'DESC')->paginate(10);

        // foreach($all_enroll as $enroll){
        //     $array[] = $enroll->trainee->company->company;
        // }

        // dd($array);


        $count_enroll = $query->count();


        return view('livewire.company.enroll.c-confirm-enrollment-component', [
            'all_enroll' => $all_enroll,
            'count_enroll' => $count_enroll,
        ])->layout('layouts.admin.abase');
    }
}
