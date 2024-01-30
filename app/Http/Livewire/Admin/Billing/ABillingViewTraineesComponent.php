<?php

namespace App\Http\Livewire\Admin\Billing;

use App\Mail\SendBillingStatementToClient;
use App\Mail\SendBillingStatementToGM;
use App\Mail\SendOfficialReceipt;
use App\Models\billingattachment;
use App\Models\billingattachmenttype;
use App\Models\billingserialnumber;
use App\Models\tblbillingaccount;
use App\Models\tblbillingstatus;
use App\Models\tblcompany;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Barryvdh\DomPDF\Facade\Pdf;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\WithFileUploads;

class ABillingViewTraineesComponent extends Component
{
    use ConsoleLog;
    public $scheduleid;
    public $companyid;
    public $billingstatusid;
    public $billingstatus_name;
    public $company_name;
    public $training_date;
    public $trainees;
    //billing statement variables
    public $billingReceivingInfo;
    public $startdateformat;
    public $enddateformat;
    public $traineeList;
    public $billingSerialNumber;
    public $attachments;
    public $is_SignatureAttached;
    public $is_GmSignatureAttached;
    public $attachmenttypeid;
    public $comp_name;
    public $billingserialnumber;
    public $startdate;
    public $enddate;
    public $course;
    //
    public $titleOr;
    public $fileOr;
    protected $listeners = ['flashRequestMessage' => 'flashRequestMessage'];
    
    public function flashRequestMessage($data)
    {
            session()->flash($data['response'],$data['message']);
    }

    public function mount()
    {
        try 
        {
            $this->scheduleid = Session::get('scheduleid');
            $this->companyid = Session::get('companyid');
            $this->billingstatusid = Session::get('billingstatusid');
            
            //get attachment
            $this->getAttachment();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        try 
        {
            $trainees = $this->loadTrainees($this->scheduleid , $this->companyid);
            $enroled_data = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                            ->join('tblcourseschedule', 'tblenroled.scheduleid' , '=' , 'tblcourseschedule.scheduleid')
                            ->where('tblenroled.scheduleid', $this->scheduleid)
                            ->where('tbltraineeaccount.company_id', $this->companyid)
                            ->first();
            // if($enroled_data){
            //         $this->is_SignatureAttached = $enroled_data->is_SignatureAttached;
            //         $this->is_GmSignatureAttached = $enroled_data->is_GmSignatureAttached;
            //         $this->comp_name = $enroled_data->company;
            //         $this->billingserialnumber = $enroled_data->billingserialnumber;
            //         $this->startdate = $enroled_data->startdateformat;
            //         $this->enddate = $enroled_data->enddateformat;
            // }
            $billingstatus_data = tblbillingstatus::find($this->billingstatusid);
            $company_data = tblcompany::find($this->companyid);
            $schedule_data = tblcourseschedule::find($this->scheduleid);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.billing.a-billing-view-trainees-component' , 
        [
            'trainees' => $trainees,
            'enroled_data' => $enroled_data,
            'billingstatus_data' => $billingstatus_data,
            'company_data' => $company_data,
            'schedule_data' => $schedule_data
        ])->layout('layouts.admin.abase');
    }
    
    public function loadTrainees($schedid,$compid)
    {
        try 
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
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }
    
    //function attached or remove GM signature
    public function attachGMSignature()
    {   
        try 
        {
            if($this->is_GmSignatureAttached == 0){
                $update_value = 1;
                $update_msg = "Signature attached!";
            }
            else{
                $update_value = 0;
                $update_msg = "Signature removed!";
            }
    
            $update_signature = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->where('tblenroled.scheduleid', $this->scheduleid)
            ->where('tbltraineeaccount.company_id', $this->companyid)
            ->first();
            $update_signature->is_GmSignatureAttached = $update_value;
    
            if($update_signature->save()){
                        // Dispatch success event
                        $this->dispatchBrowserEvent('save-log', [
                            'title' => $update_msg
                        ]);
            }
    
    
            return redirect()->route('a.billing-viewtrainees');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    //upload Official Receipt
    public function uploadOfficialReceipt()
    {
        try 
        {
            $this->validate([
                    'fileOr' => 'required|mimes:jpg,png,pdf,doc,docx,ppt,xlsx|max:2048' , 
                    'titleOr' => 'required'
            ]);

            $filepath = $this->fileOr->store('uploads/billingAttachment' , 'public');
            $filenameWithExtension = $this->fileOr->getClientOriginalName();
            // dd($filepath);

            if($filepath){

                $add_OR = new billingattachment();
                $add_OR->scheduleid = $this->scheduleid;
                $add_OR->companyid = $this->companyid;
                $add_OR->title = $this->titleOr;
                $add_OR->filepath = $filepath;
                $add_OR->is_deleted = 0;
                $add_OR->attachmenttypeid = 3;
                $add_OR->save();

                // send email to client
                Mail::to('sherwin.roxas@neti.com.ph')
                ->cc('sherwin.roxas@neti.com.ph')
                ->cc('daniel.narciso@neti.com.ph')
                ->cc('louise.mejico@neti.com.ph')
                ->send(new SendOfficialReceipt(
                    $this->billingserialnumber,
                    date_format(date_create($this->startdate) , "d F Y")." to ".date_format(date_create($this->enddate) , "d F Y"),
                    $filepath,
                    $filenameWithExtension,
                    $this->course
                ));

                //update billing status
                $this->updateBillingReview(5);

                // Dispatch success event
                $this->dispatchBrowserEvent('save-log', [
                    'title' => 'Official Receipt upload success!'
                ]);

            }

            return redirect()->route('a.billing-viewtrainees');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    
}
