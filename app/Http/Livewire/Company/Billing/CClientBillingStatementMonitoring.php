<?php

namespace App\Http\Livewire\Company\Billing;

use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class CClientBillingStatementMonitoring extends Component
{
    public $rowCount = [];

    //dd(Auth::user()->company_id);

    public function eachRow($billingstatusid)
    {
        $results = tbltraineeaccount::join('tblenroled', 'tbltraineeaccount.traineeid', '=', 'tblenroled.traineeid')
        ->join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
        ->join('tblcompany', 'tbltraineeaccount.company_id', '=', 'tblcompany.companyid')
        ->join('tblcourses', 'tblcourseschedule.courseid', '=', 'tblcourses.courseid')
        ->where(function ($query) use ($billingstatusid){
            $query->where('tblenroled.billingstatusid', '=', $billingstatusid)
                ->where('tbltraineeaccount.company_id', '=', Auth::user()->company_id)
                ->where('tblcourseschedule.startdateformat', 'like', '%2023%');
        })
        ->groupBy('tblcourseschedule.scheduleid', 'tblcompany.companyid', 'tblcourses.coursecode', 'tblcourses.coursename', 
        'tblcourseschedule.startdateformat', 'tblcourseschedule.enddateformat', 
        'tblcompany.company' , 'tblenroled.billingserialnumber' )
        ->orderBy('tblcourseschedule.startdateformat', 'ASC')
        ->select('tblcourses.coursecode', 'tblcourses.coursename', 'tblcourseschedule.scheduleid', 'tblcourseschedule.startdateformat', 
        'tblcourseschedule.enddateformat', 'tblcompany.company', 'tblcompany.companyid' , 'tblenroled.billingserialnumber')
        ->get();

        return $results;
    }

    public function passSessionData($billingstatusid)
    {
        Session::put('billingstatusid' , $billingstatusid);

        return redirect()->route('c.client-billing-view-schedule');
    }


    public function render()
    {   
        $bsIssued = $this->eachRow(2);
        $bsReceived = $this->eachRow(3);
        $paymentSent = $this->eachRow(4);
        $orIssued = $this->eachRow(5);
        $transactionClosed = $this->eachRow(6);

        return view('livewire.company.billing.c-client-billing-statement-monitoring',
        [
            'bsIssued' => $bsIssued,
            'bsReceived' => $bsReceived, 
            'paymentSent' => $paymentSent, 
            'orIssued' => $orIssued, 
            'transactionClosed' => $transactionClosed
        ])->layout('layouts.admin.abase');
    }
}
