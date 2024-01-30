<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Models\tblbillingstatus;
use App\Models\tblcourseschedule;
use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class ABillingViewComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    public $billingstatusid;
    public $billingstatus_name;
    public $billingstatus_desc;
    public $search;
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        $this->billingstatusid = Session::get('billingstatusid');
    }

    public function passSessionData($scheduleid,$companyid)
    {
        Session::put('billingstatusid' , $this->billingstatusid);
        Session::put('scheduleid' , $scheduleid);
        Session::put('companyid' , $companyid);

        return redirect()->route('a.billing-viewtrainees');
    }
    
    public function render()
    {
        $billingstatus_data = tblbillingstatus::find($this->billingstatusid);
        try 
        {
            $query = tbltraineeaccount::join('tblenroled', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
            ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
            ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
            ->where(function ($query) {
                $query->where('tblenroled.billingstatusid', '=', $this->billingstatusid)
                    ->where('tbltraineeaccount.company_id', '!=', 1)
                    ->where('tblcourseschedule.startdateformat', '>', '2023-12-31');
            })
            ->orWhere(function ($query) {
                $query->where('tblenroled.billingstatusid', '=', $this->billingstatusid)
                    ->where('tbltraineeaccount.company_id', '=', 1)
                    ->where('tblcourseschedule.startdateformat', '>', '2023-12-31')
                    ->whereIn('tblcourseschedule.courseid', [91, 92]);
            })
            ->groupBy('tblcourseschedule.scheduleid', 'tblcompany.companyid', 'tblcourses.coursecode', 'tblcourses.coursename', 
            'tblcourseschedule.startdateformat', 'tblcourseschedule.enddateformat', 
            'tblcompany.company' , 'tblenroled.billingserialnumber' )
            ->orderBy('tblcourseschedule.startdateformat', 'ASC')
            ->select('tblcourses.coursecode', 'tblcourses.coursename', 'tblcourseschedule.scheduleid', 'tblcourseschedule.startdateformat', 
            'tblcourseschedule.enddateformat', 'tblcompany.company', 'tblcompany.companyid' , 'tblenroled.billingserialnumber');

            if(!empty($this->search))
            {
                $query->where(function ($q){
                        $q->orWhere('tblcompany.company', 'like', '%' . $this->search . '%')
                        ->orWhere('tblenroled.billingserialnumber', 'like', '%' . $this->search . '%');
                    });
            }

            $schedules = $query->paginate(10);

            $startNo = ($schedules->currentPage() - 1) * $schedules->perPage(10) + 1;
            $t_allschedules = $schedules->total();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.billing.a-billing-view-component', 
                        [
                            't_allschedules' => $t_allschedules,
                            'schedules' => $schedules,
                            'startNo' => $startNo,
                            'billingstatus_data' => $billingstatus_data
                        ])->layout('layouts.admin.abase');
    }
    
}
