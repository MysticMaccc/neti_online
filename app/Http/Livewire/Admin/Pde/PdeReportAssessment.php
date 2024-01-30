<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Mail\SendPdeAssessment;
use App\Models\tblcoursedepartment;
use App\Models\tblinstructor;
use App\Models\tblpde;
use App\Models\tblpdecertificatenumbercounter;
use App\Models\tblpdereq;
use App\Models\tblrank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use PDF;

class PdeReportAssessment extends Component
{

    use WithFileUploads;
    use WithPagination;
    use ConsoleLog;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $pdeid;

    public $selectedAssessor;
    public $selectedDepartmenthead;
    public $withDeptHeadSignature;
    public $withGMSignature;

    public $editfirstname;
    public $editmiddlename;
    public $editlastname;
    public $editsuffix;
    public $editbirthday;
    public $editage;
    public $editselectedPosition;
    public $editvessels;
    public $editpassportno;
    public $passportexpirydate;
    public $medicalexpirydate;
    public $fileattachment;

    public $rankid;
    public $compliant = [];
    public $remarks = [];
    public $assessmentresult;


    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes for $search
    }

    public function pdeedit($pdeid)
    {
        try {
            $pde_data = tblpde::find($pdeid);

            if ($pde_data) {

                $this->pdeid = $pde_data->pdeID;
                $this->editfirstname = $pde_data->givenname;
                $this->editmiddlename = $pde_data->middlename;
                $this->editlastname = $pde_data->surname;
                $this->editsuffix = $pde_data->suffix;
                $this->editbirthday = $pde_data->dateofbirth;
                $this->editage = $pde_data->age;
                $this->editselectedPosition = $pde_data->rankid;
                $this->editvessels = $pde_data->vessel;
                $this->editpassportno = $pde_data->passportno;
                $this->passportexpirydate = $pde_data->passportexpirydate;
                $this->medicalexpirydate = $pde_data->medicalexpirydate;
                // $this->fileattachment = $pde_data->fileattachment;

            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }



    public function pdeupdate()
    {
        try {
            $update_pde = tblpde::find($this->pdeid);
            $originalFileName = null;

            if ($update_pde) {
                if ($this->fileattachment !== null) { // Check if fileattachment is not null
                    $originalFileName = $this->fileattachment->getClientOriginalName();
                    $newFileName = $this->fileattachment->hashName();
                    $this->fileattachment->storeAs('public/uploads/pdefiles', $newFileName);
                    $update_pde->attachmentpath = $newFileName;
                }

                $update_pde->givenname = $this->editfirstname;
                $update_pde->middlename = $this->editmiddlename;
                $update_pde->surname = $this->editlastname;
                $update_pde->suffix = $this->editsuffix;
                $update_pde->dateofbirth = $this->editbirthday;

                if ($this->editbirthday) {
                    $dob = new \DateTime($this->editbirthday);
                    $today = new \DateTime();
                    $editage = $today->diff($dob)->y;
                    $update_pde->age = $editage;
                }

                $position = tblrank::find($this->editselectedPosition);
                $update_pde->rankid = $this->editselectedPosition;
                $update_pde->position = $position->rank;
                $update_pde->vessel = $this->editvessels;
                $update_pde->passportno = $this->editpassportno;
                $update_pde->passportexpirydate = $this->passportexpirydate;
                $update_pde->medicalexpirydate = $this->medicalexpirydate;
                $update_pde->attachment_filename = $originalFileName;

                $update_pde->save(); // Save the model

                $this->dispatchBrowserEvent('d_modal', [
                    'id' => '#editModal',
                    'do' => 'hide'
                ]);
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }



    public function datainpdf()
    {
        try {
            $pderequirementsarray = [];
            $departmenthead = tblcoursedepartment::find($this->selectedDepartmenthead);
            $retrievepderequirements = tblpdereq::where('rankid', $this->rankid)->where('deletedid', 0)->get();
            $assessor = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
                ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
                ->where('users.user_id', $this->selectedAssessor)
                ->first();

            if ($assessor) {
                $full_name = $assessor->rankacronym . ' ' . $assessor->f_name . ' ' . $assessor->l_name;
                $assessorsemail = $assessor->email;

                // Set the 'assessorname' session variable
                session(['assessorname' => $full_name]);

                // Check if 'SignaturePath' property exists and is not empty
                if (isset($assessor->SignaturePath) && !empty($assessor->SignaturePath)) {
                    session(['assessoresign' => $assessor->SignaturePath]);
                } else {
                    // Handle the case where 'SignaturePath' is not available
                    session(['assessoresign' => '']);
                }

                // Set 'PDECertAssessorID' only if an assessor is found
                session(['PDECertAssessorID' => $assessor->userid]);
            } else {
                // Handle the case where no assessor is found
                session(['assessorname' => '']);
                session(['assessoresign' => '']);
                session(['PDECertAssessorID' => null]); // Set to null or any default value
            }

            session(['retrievepderequirements' => $retrievepderequirements]);

            session(['assessorsemail' => $assessorsemail]);
            session(['pdeid' => $this->pdeid]);
            // session(['departmenthead' => $this->selectedDepartmenthead]);
            session(['PDECertDeptHeadID' => $departmenthead->coursedepartmentid]);
            session(['departmentheadname' => $departmenthead->departmenthead]);
            session(['departmentheademail' => $departmenthead->email]);
            session(['departmentheadesign' => $departmenthead->esign]);
            session(['withDHSigniture' => $this->withDeptHeadSignature]);
            session(['withGMSignature' => $this->withGMSignature]);
            // session(['printedby' => Auth::user()->formal_name() ]);
            session(['referencenumber' => $this->getlastcernumber()]);
            // session(['dateprinted' => Carbon::now('Asia/Manila')->toDateTimeString()]);

            foreach ($this->compliant as $pderequirementsid) {
                $sessionKey = $pderequirementsid;
                session(['sessionKey' => $sessionKey]);
                $pderequirementsarray[] = $sessionKey;
            }
            $pderequirementsarray = array_reverse($pderequirementsarray);
            session(['pderequirementsarray' => $pderequirementsarray]);

            // $pderequirementsarrayremarks = []; // Initialize as an empty array


            foreach ($this->remarks as $pderequirementsdetails) {
                $sessionKey = $pderequirementsdetails;
                session(['sessionKey' => $sessionKey]);
                $pderequirementsarrayremarks[] = $sessionKey;
            }
            $pderequirementsarrayremarks = array_reverse($pderequirementsarrayremarks);
            // Store the reversed array in the session
            session(['pderequirementsarrayremarks' => $pderequirementsarrayremarks]);


            // dd(session('pderequirementsarrayremarks'));



            session(['assessmentresult' => $this->assessmentresult]);



            //ttawag ng method sa agenerate pde
            return redirect()->route('a.pdereportgenerateassessment', [
                'pdeid' => $this->pdeid,

            ]);

            //fetch sa table call last in tablle
            // $lasttable = tblpde
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function getlastcernumber()
    {
        try {
            $query = tblpdecertificatenumbercounter::where('id', 1)->first();

            if ($query) {
                $lastcertnumber = $query->PDECertificateNumberCounter + 1;

                // Format the certificate number
                $lastcertnumber = str_pad($lastcertnumber, 4, '0', STR_PAD_LEFT);

                return $lastcertnumber;
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    // public function directorypdf(){
    //     $lastInsertedPDE = tblpde::latest('pdeID')->value('pdeID');
    //     $latestData = tblpde::where('pdeID', $lastInsertedPDE)->value('assessmentpdf');

    //     dd($latestData);

    //     return $latestData;
    // }
    public function sendemail()

    {
        try {
            //Department Head Details  
            $departmenthead = tblcoursedepartment::find($this->selectedDepartmenthead);
            $departmentheadname = $departmenthead->departmenthead;
            $departmentheademail = $departmenthead->email;
            $retrievepderequirements = tblpdereq::where('rankid', $this->rankid)->where('deletedid', 0)->get();


            // foreach ($this->remarks as $pderequirementsdetails) {
            //     $sessionKey = $pderequirementsdetails;
            //     $pderequirementsarrayremarks[] = $sessionKey;
            // }

            //Remarks
            $pderequirementsarrayremarks = [];
            foreach ($this->remarks as $pderequirementsdetails) {
                $sessionKey = $pderequirementsdetails;
                array_unshift($pderequirementsarrayremarks, $sessionKey);
            }



            //Assessor Details 
            $assessor = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
                ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
                ->where('users.user_id', $this->selectedAssessor)
                ->first();

            $assessorsname = '';
            $PDECertAssessorID = null;

            if ($assessor) {
                $assessorsname = $assessor->rankacronym . ' ' . $assessor->f_name . ' ' . $assessor->l_name;
                $PDECertAssessorID = $assessor->userid;
                $assessorsemail = $assessor->email;
            }

            $pdeid = $this->pdeid;

            $pde_data = tblpde::where('pdeid', $pdeid)
                ->join('tblrank', 'tblpde.position', '=', 'tblrank.rank')
                //  ->join('tblcoursedepartment', 'tblcoursedepartment.coursedepartmentid', '=', 'tblrank.rankdepartmentid')
                //  ->join('tblcompany', 'tblcompany.companyid', '=', 'tblpde.companyid')
                ->first();
            //  $pdecrewname = $pde_data->rankacronym . ' ' . $pde_data->givenname . ' ' . $pde_data->middlename . ' ' . $pde_data->surname;


            if (!empty($pde_data)) {
                $pdecrewname = $pde_data->rankacronym . ' ' . $pde_data->givenname . ' ' . $pde_data->middlename . ' ' . $pde_data->surname;
                //  $pdecrewrank = 
            } else {
                $pdecrewname = '';
            }



            $crewattachment = 'storage/uploads/pdefiles/' . $pde_data->attachmentpath;

            // // $pdf = PDF::('a.pdereportgenerateassessment', [
            // //     'pdeid' => $this->pdeid,

            // // ]);
            // // $pdf = PDF::loadView('a.pdereportgenerateassessment', ['pdeid' => $this->pdeid]);
            //     $pdf =   $this->datainpdf();
            //     $filePath = $pdf->store('uploads/pdefiles', 'public');

            //     dd($filePath );



            $recipientEmail = $assessorsemail;
            $ccEmails = $departmentheademail; // Replace with the CC recipient's email addresses
            $bccEmails = ['louise.mejico@neti.com.ph'];

            Mail::to($recipientEmail)
                ->cc($ccEmails)
                ->bcc($bccEmails)
                ->send(new SendPdeAssessment(
                    $departmentheadname,
                    $assessorsname,
                    $pdecrewname,
                    $retrievepderequirements,
                    $pderequirementsarrayremarks,
                    $crewattachment
                ));
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }



    public function pdegenerateassessment($pdeid)
    {
        try {
            $pde_data = tblpde::find($pdeid);

            if ($pde_data) {
                $this->pdeid = $pde_data->pdeID;
                $this->rankid = $pde_data->rankid;
            }
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }


    public function render()
    {
        try {
            $retrievepderequirements = tblpdereq::where('rankid', $this->rankid)
                ->where('deletedid', 0)->get();






            //RETRIEVE RANK LEVEL
            $retrieverank = tblrank::where('IsPDECert', 1)
                ->orderBy('rank', 'asc')
                ->get();



            $retrieveAssessors = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
                ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')
                ->where('isPDEAssessor', 1)
                ->orderBy('tblrank.rankacronym', 'asc') // Order by 'rank' in ascending order
                ->orderBy('users.l_name', 'asc')  // Then order by 'l_name' in ascending order
                ->get();

            $retrieveDepartmenthead = tblcoursedepartment::orderBy('departmenthead', 'asc')->get();


            // Retrieve PDE assessments
            $query = tblpde::with('company')
                ->whereIn('statusid', [1, 2, 3, 4])
                ->where('deletedid', 0);


            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('surname', 'like', '%' . $this->search . '%');
                    $q->orWhere('givenname', 'like', '%' . $this->search . '%');
                    $q->orWhere('middlename', 'like', '%' . $this->search . '%');
                });
            }
            $query->orderBy('created_at', 'desc');

            $mypdeassessments = $query->paginate(20);

            //Download Document File 
            $timestamp = now()->format('YmdHis');
            // $desiredFilename = 'document_' . $timestamp . '.zip';


            $desiredFilename = 'document_' . $timestamp . '.zip';
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.pde.pde-report-assessment', [
            'mypdeassessments' => $mypdeassessments,
            'retrieveAssessors' => $retrieveAssessors,
            'retrieverank' => $retrieverank,
            'retrieveDepartmenthead' => $retrieveDepartmenthead,
            'desiredFilename' => $desiredFilename,
            'retrievepderequirements' => $retrievepderequirements

        ])->layout('layouts.admin.abase');
    }
}
