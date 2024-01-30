<?php

namespace App\Http\Livewire\Company\Billing;

use App\Mail\SendBillingStatementConfirmation;
use App\Mail\SendOfficialReceiptConfirmation;
use App\Mail\SendPaymentSlip;
use App\Models\billingattachment;
use App\Models\tblbillingstatus;
use App\Models\tblcompany;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Livewire\WithFileUploads;

class CClientBillingViewTrainees extends Component
{
    use WithFileUploads;

    public $scheduleid;
    public $companyid;
    public $billingstatusid;
    public $billingstatus_name;
    public $company_name;
    public $training_date;
    public $trainees;
    public $attachments;
    public $title;
    public $file;
    public $course;

    //email variables
    public $comp_name;
    public $billingserialnumber;
    public $startdate;
    public $enddate;

    public function mount()
    {
            $this->scheduleid = Session::get('scheduleid');
            $this->companyid = Session::get('companyid');
            $this->billingstatusid = Session::get('billingstatusid');

            $billingstatus_data = tblbillingstatus::find($this->billingstatusid);
            if($billingstatus_data){
                    $this->billingstatus_name = $billingstatus_data->billingstatus;
            }

            $company_data = tblcompany::find($this->companyid);
            if($company_data){
                    $this->company_name = $company_data->company;
            }

            $schedule_data = tblcourseschedule::find($this->scheduleid);
            if($schedule_data){
                    $this->training_date = $schedule_data->startdateformat." - ".$schedule_data->enddateformat;
                    $this->course = $schedule_data->course->coursecode." / ".$schedule_data->course->coursename;
            }
            
            //get serial number
            $enroled_data = tblenroled::join('tblcourseschedule', 'tblenroled.scheduleid', '=', 'tblcourseschedule.scheduleid')
            ->join('tbltraineeaccount', 'tblenroled.traineeid' , '=' , 'tbltraineeaccount.traineeid') 
            ->join('tblcompany', 'tbltraineeaccount.company_id' , '=' , 'tblcompany.companyid')
            ->select('tblenroled.billingserialnumber','tblcourseschedule.startdateformat','tblcourseschedule.enddateformat' , 'tblcompany.company')
            ->where('tblcourseschedule.scheduleid', $this->scheduleid )
            ->where('tbltraineeaccount.company_id', $this->companyid )
            ->first();
            if($enroled_data){
                    $this->comp_name = $enroled_data->company;
                    $this->billingserialnumber = $enroled_data->billingserialnumber;
                    $this->startdate = $enroled_data->startdateformat;
                    $this->enddate = $enroled_data->enddateformat;
            }

            //get attachment
            $this->getAttachment();
    }

    public function render()
    {
        $trainees = $this->loadTrainees($this->scheduleid , $this->companyid);

        return view('livewire.company.billing.c-client-billing-view-trainees',
        [
            'trainees' => $trainees 
        ])->layout('layouts.admin.abase');
    }


    //generate billing statement
    public function viewBillingStatement()
    {
        Session::put('scheduleid' , $this->scheduleid);
        Session::put('companyid' , $this->companyid);
        // dd($this->companyid);
        return redirect()->route('c.client-billing-statement');
    }

    public function loadTrainees($schedid,$compid)
    {
        $this->trainees = DB::table('tbltraineeaccount as a')
        ->select([
            'a.l_name',
            'a.f_name',
            'a.m_name',
            'a.suffix',
            'b.rankacronym'
        ])
        ->join('tblrank as b', 'a.rank_id', '=', 'b.rankid')
        ->join('tblenroled as x', 'x.traineeid', '=', 'a.traineeid')
        ->where('x.scheduleid', '=', $schedid)
        ->where('a.company_id', '=', $compid)
        ->orderBy('a.l_name', 'asc')
        ->get();
    }

    public function updateBillingStatus($statusId)
    {
        DB::table('tblenroled as a')
        ->join('tblcourseschedule as b', 'a.scheduleid', '=', 'b.scheduleid')
        ->join('tbltraineeaccount as c', 'c.traineeid', '=', 'a.traineeid')
        ->where('c.company_id', '=', $this->companyid)
        ->where('b.scheduleid', '=', $this->scheduleid)
        ->update(['a.billingstatusid' => $statusId ]);

        Session::put('billingstatusid' , $statusId );
        Session::put('scheduleid' , $this->scheduleid);
        Session::put('companyid' , $this->companyid);
        
    }

    //get attachments
    public function getAttachment()
    {
            $this->attachments = billingattachment::where('scheduleid' , $this->scheduleid)
                                 ->where('companyid' , $this->companyid)
                                 ->where('is_deleted' , 0)
                                 ->get();
    }

    //confirm receipt of billing statement
    public function confirmBillingStatement()
    {
        //send email to bod
        Mail::to('sherwin.roxas@neti.com.ph')
            ->cc('sherwin.roxas@neti.com.ph')
            ->cc('daniel.narciso@neti.com.ph')
            ->cc('louise.mejico@neti.com.ph')
            ->send(new SendBillingStatementConfirmation( 
                $this->billingserialnumber,
                $this->comp_name,
                date_format(date_create($this->startdate) , "d F Y")." to ".date_format(date_create($this->enddate) , "d F Y"),
                $this->course
             ));

        
        //update billing statusid
        $this->updateBillingStatus(3);

        // Dispatch success event
        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Billing statement confirmed'
        ]);

        return redirect()->route('c.client-billing-view-trainees');

    }

    public function confirmOfficialReceipt()
    {
            //send email to bod
            Mail::to('sherwin.roxas@neti.com.ph')
                ->cc('sherwin.roxas@neti.com.ph')
                ->cc('daniel.narciso@neti.com.ph')
                ->cc('louise.mejico@neti.com.ph')
                ->send(new SendOfficialReceiptConfirmation(
                    $this->billingserialnumber,
                    $this->comp_name,
                    date_format(date_create($this->startdate) , "d F Y")." to ".date_format(date_create($this->enddate) , "d F Y"),
                    $this->course
                ));

            //update billing statusid
            $this->updateBillingStatus(6);

            // Dispatch success event
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Official receipt confirmed'
            ]);

            return redirect()->route('c.client-billing-view-trainees');
    }

    //upload payment slip
    public function uploadPaymentSlip()
    {
            $this->validate([
                'file' => '' , 
                'title' => 'required'
            ]);

            $filepath = $this->file->store('uploads/billingAttachment' , 'public');
            $filenameWithExtension = $this->file->getClientOriginalName();

            if($filepath){

                // save file to database
                $add_paymentslip = new billingattachment();
                $add_paymentslip->scheduleid = $this->scheduleid;
                $add_paymentslip->companyid = $this->companyid;
                $add_paymentslip->title = $this->title;
                $add_paymentslip->filepath = $filepath;
                $add_paymentslip->is_deleted = 0;
                $add_paymentslip->attachmenttypeid = 2;
                $add_paymentslip->save();

                //send email to bod
                //send email to bod
                Mail::to('sherwin.roxas@neti.com.ph')
                ->cc('sherwin.roxas@neti.com.ph')
                ->cc('daniel.narciso@neti.com.ph')
                ->cc('louise.mejico@neti.com.ph')
                ->send(new SendPaymentSlip( 
                    $this->billingserialnumber,
                    $this->comp_name,
                    date_format(date_create($this->startdate) , "d F Y")." to ".date_format(date_create($this->enddate) , "d F Y"),
                    $filepath,
                    $filenameWithExtension,
                    $this->course
                ));
                

                //update billing statusid
                $this->updateBillingStatus(4);

                // Dispatch success event
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Payment slip uploaded successfully'
                ]);

            }

            return redirect()->route('c.client-billing-view-trainees');
    }



}
