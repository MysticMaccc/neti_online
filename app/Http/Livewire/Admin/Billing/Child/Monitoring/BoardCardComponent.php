<?php
namespace App\Http\Livewire\Admin\Billing\Child\Monitoring;

use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class BoardCardComponent extends Component
{
    public $billingstatusid;
    public $icon;
    public $step;
    public $process;

    public function render()
    {
            $trainee_data = $this->eachRow($this->billingstatusid);
            return view('livewire.admin.billing.child.monitoring.board-card-component', compact('trainee_data'));
    }

    public function passSessionData($id)
    {
        Session::put('billingstatusid' , $id);

        return redirect()->route('a.billing-view');
    }

    public function eachRow($statusid)
    {
        try 
        {
            $query = tbltraineeaccount::join('tblenroled', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
            ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
            ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
            ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
            ->where(function ($query) use ($statusid) {
                $query->where('tblenroled.billingstatusid', '=', $statusid)
                    ->where('tbltraineeaccount.company_id', '!=', 1)
                    ->where('tblcourseschedule.startdateformat', '>', '2023-12-31');
            })
            ->orWhere(function ($query) use ($statusid) {
                $query->where('tblenroled.billingstatusid', '=', $statusid)
                    ->where('tbltraineeaccount.company_id', '=', 1)
                    ->where('tblcourseschedule.startdateformat', '>', '2023-12-31')
                    ->whereIn('tblcourseschedule.courseid', [91, 92]);
            })
            ->groupBy('tblcourseschedule.scheduleid', 'tblcompany.companyid', 'tblcourses.coursecode', 'tblcourses.coursename', 
            'tblcourseschedule.startdateformat', 'tblcourseschedule.enddateformat', 
            'tblcompany.company' , 'tblenroled.billingserialnumber' )
            ->orderBy('tblcourseschedule.startdateformat', 'ASC')
            ->select('tblcourses.coursecode', 'tblcourses.coursename', 'tblcourseschedule.scheduleid', 'tblcourseschedule.startdateformat', 
            'tblcourseschedule.enddateformat', 'tblcompany.company', 'tblcompany.companyid' , 'tblenroled.billingserialnumber')
            ->get();

            return $query;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }
}
