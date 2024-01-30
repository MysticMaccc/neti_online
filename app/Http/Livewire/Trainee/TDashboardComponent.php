<?php

namespace App\Http\Livewire\Trainee;

use App\Models\tblannouncement;
use App\Models\tblcertificatehistory;
use App\Models\tblcourses;
use App\Models\tbldocuments;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\WithPagination;
use Livewire\Component;


class TDashboardComponent extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';


    protected  $enrolled_courses;
    public $certificates;
    public $documents;
    public $handout_password;
    public $handoutPassword_data;
    public $handout_path;
    protected  $completed_trainings;


    public function render()
    {
        $user = Auth::guard('trainee')->user();
        $announcement = tblannouncement::first();

        // dd($this->completed_trainings);
        $this->documents = tbldocuments::where('userid', $user->traineeid)->get();

        // dd($this->documents);
        $this->certificates = tblcertificatehistory::where('traineeid', $user->traineeid)->get();

        // $enrolled_courses  = tblcourses::select(
        //     'tblcourses.courseid',
        //     'tblcourses.coursename',
        //     'tblcourses.trainingdays',
        //     'tblenroled.pendingid',
        //     'tblenroled.scheduleid',  // Added scheduleid
        //     'tblcourseschedule.batchno'
        // )
        //     ->join('tblenroled', 'tblenroled.courseid', '=', 'tblcourses.courseid')
        //     ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')  // Join tblcourseschedule
        //     ->where('tblenroled.traineeid',  $user->traineeid)
        //     ->where('tblenroled.deletedid', 0)
        //     ->where('tblenroled.passid', 0)
        //     ->paginate(5);

        
        $enrolled_courses  = tblenroled::where('traineeid',  $user->traineeid)
            ->where('deletedid', 0)
            ->where('passid', 0)
            ->paginate(5);

        $completed_trainings = tblenroled::select(
            'tblcourses.courseid',
            'tblcourses.coursename',
            'tblcourses.trainingdays',
            'tblenroled.pendingid',
            'tblenroled.passid',
            'tblenroled.scheduleid',
            'tblcourseschedule.batchno'
            
        )
            ->join('tblcourses', 'tblenroled.courseid', '=', 'tblcourses.courseid')
            ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
            ->where('tblenroled.traineeid',  $user->traineeid)
            ->where('tblenroled.passid', 1)
            ->orWhere('tblenroled.passid', 2)
            ->get();

        return view(
            'livewire.trainee.t-dashboard-component',
            [
                'enrolled_courses' => $enrolled_courses,
                'completed_trainings' => $completed_trainings,
                'announcement' => $announcement
            ]
        )->layout('layouts.trainee.tbase');
    }

    //verify handout password
    public function getHandoutPassword(tblcourses $course)
    {
        $this->handoutPassword_data = $course->handout_password;
        $this->handout_path = $course->handoutpath;
    }
    public function verifyHandoutPassword()
    {
            if($this->handout_password == $this->handoutPassword_data)
            {
                Session::put('handoutpath' , $this->handout_path);

                return redirect()->route('t.handout');
                
            }
            else
            {
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Wrong password!'
                ]);
    
    
                $this->dispatchBrowserEvent('d_modal',[
                    'id' => '#handoutpasswordmodal',
                    'do' => 'hide'
                ]);
            }
    }

    public function goToLMS($scheduleid)
    {
        Session::put('scheduleid' , $scheduleid);
        return redirect()->route('t.lms-home');
    }


    public function redirectToCertHistoryDetails($cert_id)
    {
        Session::put('cert_id', $cert_id);
        return redirect()->to('/certificates/history');
    }
}
