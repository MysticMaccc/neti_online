<?php

namespace App\Http\Livewire\Admin\Billing\Child\GenerateBilling;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\tblcompany;
use App\Models\tblenroled;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\tblbillingaccount;
use App\Models\tblcourseschedule;
use Illuminate\Support\Facades\DB;
use App\Models\billingserialnumber;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendBillingStatementToGM;
use Illuminate\Support\Facades\Session;

class UpdateBillingStatusComponent extends Component
{
    public $title;
    public $scheduleid;
    public $companyid;
    public $defaultBankId;
    public $updateStatus;
    
    public function render()
    {
        return view('livewire.admin.billing.child.generate-billing.update-billing-status-component');
    }
    
    public function updateStatus($statusId)
    {
        try {
            $date = Carbon::now();

            $company_data = tblcompany::find($this->companyid);
            $schedule_data = tblcourseschedule::find($this->scheduleid);
            $enroled_data = tblenroled::where('scheduleid', $this->scheduleid)
                                       ->whereHas('trainee', function($query){
                                                $query->where('company_id', $this->companyid);
                                       })
                                       ->first();
            $bank_data = tblbillingaccount::find($this->defaultBankId);
            
            if ($schedule_data) {
                $startdateformat = $schedule_data->startdateformat;
                $enddateformat = $schedule_data->enddateformat;
            }

            //check if there is billing number
            if ($enroled_data->billingserialnumber == null) //generate new billing number
            {
                $newSerialNumber = $this->retrieveBillingSerialNumber();
                $billingSerialNumber = $date->format('ym') . "-" . $newSerialNumber;

                //save serial number
                $this->saveSerialNumber($this->scheduleid, $this->companyid, $billingSerialNumber);
                //update serial number table
                $this->updateSerialNumberTable();
            } else //use retrieved serialnumber
            {
                $billingSerialNumber = $enroled_data->billingserialnumber;
            }

            // get trainee list table
            $traineeList = DB::table('tblrank as a')
                ->select('a.rankacronym', 'b.l_name', 'b.f_name', 'b.m_name', 'b.suffix', 'c.rateusd')
                ->join('tbltraineeaccount as b', 'a.rankid', '=', 'b.rank_id')
                ->join('tblenroled as x', 'x.traineeid', '=', 'b.traineeid')
                ->join('tblcourseschedule as z', 'z.scheduleid', '=', 'x.scheduleid')
                ->join('tblcompanycourse as c', function ($join) {
                    $join->on('c.companyid', '=', 'b.company_id')
                        ->where('c.courseid', '=', DB::raw('z.courseid'));
                })
                ->where([
                    ['b.company_id', '=', $this->companyid],
                    ['z.scheduleid', '=', $this->scheduleid],
                    ['x.pendingid', '=', 0],
                    ['x.deletedid', '=', 0],
                ])
                ->get();
                
            $data = [
                'company_data' => $company_data,
                'schedule_data' => $schedule_data,
                'startdateformat' => date_format(date_create($startdateformat), 'd M Y'),
                'enddateformat' => date_format(date_create($enddateformat), 'd M Y'),
                'traineeList' => $traineeList,
                'billingserialnumber' => $billingSerialNumber,
                'bank_data' => $bank_data,
                'enroled_data' => $enroled_data
            ];

            $pdf = PDF::loadView('livewire.admin.generate-docs.generate-billing-statement', $data);
            $pdf->setPaper('a4', 'portrait');


            if ($statusId == 3) 
            { 
                $msgTitle = "Billing Statement sent to Finance!";
                $subject = "Billing Statement Review (Finance Staff)";
                $send_email = Mail::to(env('EMAIL_FINANCE'))
                            ->cc('sherwin.roxas@neti.com.ph')
                            ->send(new SendBillingStatementToGM(
                                $pdf,
                                $billingSerialNumber,
                                $company_data->company,
                                date_format(date_create($startdateformat), "d F Y") . " to " . date_format(date_create($enddateformat), "d F Y"),
                                $schedule_data->course->coursecode . " / " . $schedule_data->course->coursename,
                                $subject
                            ));
                if($send_email){
                    $this->update($statusId,$msgTitle);
                }  
            } 
            else if ($statusId == 4) 
            { 
                $msgTitle = "Billing Statement sent to BOD Manager!";
                $subject = "Billing Statement Review (BOD Manager)";
                $send_email = Mail::to(env('EMAIL_BOD_MANAGER'))
                            ->cc('sherwin.roxas@neti.com.ph')
                            ->send(new SendBillingStatementToGM(
                                $pdf,
                                $billingSerialNumber,
                                $company_data->company,
                                date_format(date_create($startdateformat), "d F Y") . " to " . date_format(date_create($enddateformat), "d F Y"),
                                $schedule_data->course->coursecode . " / " . $schedule_data->course->coursename,
                                $subject
                            ));
                if($send_email){
                    $this->update($statusId,$msgTitle);
                }  
            } 
            else if ($statusId == 5) 
            { 
                $msgTitle = "Billing Statement sent to GM!";
                $subject = "Billing Statement Review (General Manager)";
                $send_email = Mail::to(env('EMAIL_GM'))
                            ->cc('sherwin.roxas@neti.com.ph')
                            ->send(new SendBillingStatementToGM(
                                $pdf,
                                $billingSerialNumber,
                                $company_data->company,
                                date_format(date_create($startdateformat), "d F Y") . " to " . date_format(date_create($enddateformat), "d F Y"),
                                $schedule_data->course->coursecode . " / " . $schedule_data->course->coursename,
                                $subject
                            ));
                if($send_email){
                    $this->update($statusId,$msgTitle);
                }  
            }

        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function update($statusId,$msgTitle)
    {
        try {
            $update = DB::table('tblenroled as a')
                ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
                ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid')
                ->where('c.company_id', '=', $this->companyid)
                ->where('b.scheduleid', '=', $this->scheduleid)
                ->update(['a.billingstatusid' => $statusId]);
            if(!$update){
                $this->dispatchBrowserEvent('error-log', [
                    'title' => 'Failed to update billing'
                ]);
            }
            
            $this->dispatchBrowserEvent('save-log', [
                'title' => $msgTitle
            ]);
            Session::put('billingstatusid', $statusId);
            Session::put('scheduleid', $this->scheduleid);
            Session::put('companyid', $this->companyid);
            return redirect()->route('a.billing-viewtrainees');

        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }
    
    // billing statement functions
    // billing statement functions
    public function updateSerialNumberTable()
    {
        try 
        {
            DB::update("UPDATE billingserialnumber SET serialnumber = serialnumber + 1 WHERE id = 1");
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function saveSerialNumber($schedID,$compID,$sN)
    {
        try 
        {
            DB::table('tblenroled as a')
            ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
            ->join('tbltraineeaccount as x', 'x.traineeid', '=', 'a.traineeid')
            ->where('b.scheduleid', $schedID)
            ->where('x.company_id', $compID)
            ->update(['a.billingserialnumber' => $sN]);   
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function retrieveBillingSerialNumber()
    {
        try 
        {
            $getLastSerialNumber = billingserialnumber::find(1);

            if($getLastSerialNumber){
                $newSerialNumber = $getLastSerialNumber->serialnumber + 1 ; 

                switch (strlen($newSerialNumber)){
                    case '1':  $newSerialNumber = "000".$newSerialNumber; break;
                    case '2':  $newSerialNumber = "00".$newSerialNumber; break;
                    case '3':  $newSerialNumber = "0".$newSerialNumber; break; 
                    default: $newSerialNumber = $newSerialNumber; break;
                }
            }

            return $newSerialNumber;   
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }
    // billing statement functions end
    // billing statement functions end
    
}
