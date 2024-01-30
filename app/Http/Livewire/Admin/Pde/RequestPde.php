<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Mail\SendPdeRequest;
use App\Models\tblpde;
use App\Models\tblpdetblpderequirements;
use App\Models\tblrank;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithFileUploads;
use PhpOffice\PhpSpreadsheet\Chart\Layout;

class RequestPde extends Component
{

    use WithFileUploads;
    use ConsoleLog;
    use AuthorizesRequests;
    public $pderequirementsid;
    public $title;
    public $path;
    public $dateOfBirth;
    public $rows = [];
    public $surname;
    public $firstname;
    public $middlename;
    public $suffix;
    public $selectedPosition;
    public $vessels;
    public $age;
    public $passport;
    public $passportexpirydate;
    public $medicalexpirydate;
    public $fileattachment;
    public $selectedRowIndex;


    //COMPUTE AGE
    protected $rules = [
        'dateOfBirth' => 'date',
        'age' => 'numeric|min:18|max:100',
        'selectedPosition' => 'required',
    ];

    // public function calculateAge()
    // {
    //     $age = null;
    //     if ($this->dateOfBirth) {
    //         $dob = new \DateTime($this->dateOfBirth);
    //         $today = new \DateTime();
    //         $age = $today->diff($dob)->y;
    //         $this->age = $age;
    //     }
    // }

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 16);
    }

    //PDE REQUIREMENTS VIEW IN MODAL 
    public function pderequirementsview($pderequirementsid)
    {
        try 
        {
            $pdereq_view = tblpdetblpderequirements::where('pderequirementsid', $pderequirementsid)->first();
            if ($pdereq_view) {
                $this->pderequirementsid = $pdereq_view->pderequirementsid;
                $this->title = $pdereq_view->title;
                $this->path = $pdereq_view->path;
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }



    public function addRow()
    {

        // $rules = [
        //     'selectedPosition' => 'nullable',
        // ];

        // $messages = [
        //     'selectedPosition.required' => 'The select position is required.',

        // ];

        //       // Run the validation
        //       $validator = Validator::make([

        //         'selectedGender' => $this->selectedPosition,

        //     ], $rules, $messages);

        //     // Check if validation fails
        //     if ($validator->fails()) {
        //         $this->addErrorMessages($validator->errors()->messages());
        //         return;
        //     }

        try 
        {
            $positionId = $this->selectedPosition;
            $position = tblrank::find($this->selectedPosition);
            $dateOfBirth = $this->dateOfBirth;

            $position = tblrank::find($positionId);

            $age = null;
            if ($dateOfBirth) {
                $dob = new \DateTime($dateOfBirth);
                $today = new \DateTime();
                $age = $today->diff($dob)->y;
            }

            $this->rows[] = [
                'requestaccountdesignation' => null,
                'requestby' => Auth::user()->formal_name(),
                'requestfleet' => Auth::user()->fleet_id, //'Fleet A-2', // FROM NYK FIL -> FLEET A-2
                'pdestatusid' => 1, // 1 IS FOR NEW OR PENDING 
                'surname' => $this->surname,
                'firstname' => $this->firstname,
                'middlename' => $this->middlename,
                'suffix' => $this->suffix,
                'selectedPosition' => $position->rank,
                'vessels' => $this->vessels,
                'statusid' => 1,
                // 'fileattachment' => null,
                'companyid' => Auth::user()->company_id, //1 = NSMI
                'age' => $age,
                'dateOfBirth' => $dateOfBirth,
                'passport' => $this->passport,
                'passportexpirydate' => $this->passportexpirydate,
                'medicalexpirydate' => $this->medicalexpirydate,
                'rankid' => $position->rankid,



            ];

            // Clear the input fields
            $this->surname = '';
            $this->firstname = '';
            $this->middlename = '';
            $this->suffix = '';
            $this->selectedPosition = '';
            $this->vessels = '';
            $this->dateOfBirth = '';
            $this->age = '';
            $this->passport = '';
            $this->passportexpirydate = '';
            $this->medicalexpirydate = '';
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }


    public function removeRow($index)
    {
        unset($this->rows[$index]);
        $this->rows = array_values($this->rows); // Re-index the array
    }


    public function setRowIndex($index)
    {
        $this->selectedRowIndex = $index;
    }

    public function formrequestpde()
    {
        try 
        {
            $emailContent = []; // Array to store email content for each row

            foreach ($this->rows as $index => $row) {
                $file = $row['fileattachment'];
    
                // Validate and process the file here (customize as needed)
                $this->validate([
                    'rows.' . $index . '.fileattachment' => 'file|mimes:zip|max:10240', // Customize validation rules
                ]);
    
                // Get the original file name
                $originalFileName = $file->getClientOriginalName();
                $newFileName = $file->hashName(); // Use $file to generate the new file name
                // Store the file in the desired directory
                $filePath = $file->storeAs('public/uploads/pdefiles', $newFileName);
    
                $add_pde = new tblpde;
                $add_pde->requestaccountdesignation = null;
                $add_pde->requestby = Auth::user()->formal_name();
                $add_pde->requestfleet = $row['requestfleet'];
                $add_pde->pdestatusid = 1;
                $add_pde->surname = $row['surname'];
                $add_pde->givenname = $row['firstname'];
                $add_pde->middlename = $row['middlename'];
                $add_pde->suffix = $row['suffix'];
                $add_pde->position = $row['selectedPosition'];
                $add_pde->vessel = $row['vessels'];
                $add_pde->statusid = 1; // 1 IS FOR NEW OR PENDING
                $add_pde->companyid = $row['companyid'];
                $add_pde->age = $row['age'];
                $add_pde->dateofbirth = $row['dateOfBirth'];
                $add_pde->passportno = $row['passport'];
                $add_pde->passportexpirydate = $row['passportexpirydate'];
                $add_pde->medicalexpirydate = $row['medicalexpirydate'];
                $add_pde->rankid = $row['rankid'];
                $add_pde->attachmentpath = $newFileName;
    
                // Save the original file name to your database
                $add_pde->attachment_filename = $originalFileName;
    
                $add_pde->save();
    
    
                $emailContent[] = [
                    'Fullname' => $row['surname'] . ', ' . $row['firstname'] . ' ' . $row['middlename'],
                    'Surname' => $row['surname'],
                    'First Name' => $row['firstname'],
                    'Middle Name' => $row['middlename'],
                    'Suffix' => $row['suffix'],
                    'Position' => $row['selectedPosition'],
                    'Rank ID' => $row['rankid'],
                    'Vessel' => $row['vessels'],
                    'Date of Birth' => $row['dateOfBirth'],
                    'Age' => $row['age'],
                    'Passport No' => $row['passport'],
                    'Passport Expiry Date' => $row['passportexpirydate'],
                    'Medical Expiry Date' => $row['medicalexpirydate'],
                    // 'Attachment Path' => $filePath,
                    'Requested By' => Auth::user()->formal_name(),
                ];
    
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'title' => 'Request sent',
                    'position' => 'middle',
                    'icon' => 'success',
                    'confirmbtn' => true,
                ]);
            }
    
            // Add row data to the email content array
            $ccEmails = ['daniel.narciso@neti.com.ph']; // Replace with the CC recipient's email addresses
            $bccEmails = ['louise.mejico@neti.com.ph']; // Replace with the BCC recipient's email addresses
    
            // Send a single email with a table containing all the data
            Mail::to('louise.mejico@neti.com.ph') // Replace with the recipient's email address
                ->cc($ccEmails)
                ->bcc($bccEmails)
                ->send(new SendPdeRequest($emailContent));
    
            // Reset the form after submission, if needed
            $this->reset(['rows']);
    
            // Redirect or show a success message as desired
            return redirect()->route('a.requestpde');
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
            $retrieverank = tblrank::where('IsPDECert', 1)
                ->orderBy('rank', 'asc')
                ->get();

            $retrievepderequirement = tblpdetblpderequirements::orderBy('title', 'asc')->get();
            // dd( Auth::user()->formal_name());
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.pde.request-pde', [
            'retrieverank' => $retrieverank,
            'retrievepderequirement' => $retrievepderequirement
        ])->layout('layouts.admin.abase');
    }
}
