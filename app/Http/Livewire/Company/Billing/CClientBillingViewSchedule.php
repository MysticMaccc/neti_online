<?php

namespace App\Http\Livewire\Company\Billing;

use App\Models\tblbillingstatus;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithPagination;

class CClientBillingViewSchedule extends Component
{
    use WithPagination;
    public $billingstatusid;
    public $billingstatus_name;
    public $billingstatus_desc;
    public $search;
    // public $schedules;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
            $this->billingstatusid = Session::get('billingstatusid');

            $billingstatus_data = tblbillingstatus::find($this->billingstatusid);
            if($billingstatus_data){
                    $this->billingstatus_name = $billingstatus_data->billingstatus;
                    $this->billingstatus_desc = $billingstatus_data->description;
            }
    }

    public function passSessionData($scheduleid,$companyid)
    {
        Session::put('billingstatusid' , $this->billingstatusid);
        Session::put('scheduleid' , $scheduleid);
        Session::put('companyid' , $companyid);

        return redirect()->route('c.client-billing-view-trainees');
    }

    public function render()
    {
        $query = tbltraineeaccount::join('tblenroled', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
        ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
        ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
        ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
        ->where(function ($query) {
            $query->where('tblenroled.billingstatusid', '=', $this->billingstatusid)
                ->where('tbltraineeaccount.company_id', '=', Auth::user()->company_id)
                ->where('tblcourseschedule.startdateformat', 'like', '%2023%');
        })
        ->groupBy('tblcourseschedule.scheduleid', 'tblcompany.companyid', 'tblcourses.coursecode', 'tblcourses.coursename', 
        'tblcourseschedule.startdateformat', 'tblcourseschedule.enddateformat', 
        'tblcompany.company' , 'tblenroled.billingserialnumber' )
        ->orderBy('tblcourseschedule.startdateformat', 'ASC')
        ->select('tblcourses.coursecode', 'tblcourses.coursename', 'tblcourseschedule.scheduleid', 'tblcourseschedule.startdateformat', 
        'tblcourseschedule.enddateformat', 'tblcompany.company', 'tblcompany.companyid' , 'tblenroled.billingserialnumber');

        if(!empty($this->search)){
            $query->where(function ($q){
                $q->orWhere('tblcompany.company', 'like', '%' . $this->search . '%')
                  ->orWhere('tblenroled.billingserialnumber', 'like', '%' . $this->search . '%');
            });
        }

        $schedules = $query->paginate(10);
        
        $startNo = ($schedules->currentPage() - 1) * $schedules->perPage(10) + 1;
        $t_allschedules = $schedules->total();


        return view('livewire.company.billing.c-client-billing-view-schedule',
        [
            't_allschedules' => $t_allschedules,
            'schedules' => $schedules,
            'startNo' => $startNo
        ])->layout('layouts.admin.abase');
    }
}
