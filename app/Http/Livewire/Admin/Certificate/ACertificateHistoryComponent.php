<?php

namespace App\Http\Livewire\Admin\Certificate;

use App\Models\tblcertificatehistory;
use App\Models\tblcourses;
use App\Models\tblenroled;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class ACertificateHistoryComponent extends Component
{
    use ConsoleLog;
    use WithPagination;
    public $search;
    public $selected_course;
    
    
    public function redirectToCertHistoryDetails($cert_id)
    {
        Session::put('cert_id', $cert_id);
        return redirect()->to('admin/certificate-history/view');
    }

    public function render()
    {

            // // Set the maximum execution time to unlimited
            // set_time_limit(0);

            // // Chunk size for processing records
            // $chunkSize = 100;

            // // Count total records
            // $totalRecords = tblcertificatehistory::count();

            // // Calculate the number of chunks
            // $numChunks = ceil($totalRecords / $chunkSize);

            // // Process records in chunks
            // for ($i = 0; $i < $numChunks; $i++) {
            //     tblcertificatehistory::skip($i * $chunkSize)
            //         ->take($chunkSize)
            //         ->get()
            //         ->each(function ($certificate) {
            //             $enroledRecord = tblenroled::where('courseid', $certificate->courseid)
            //                 ->where('traineeid', $certificate->traineeid)
            //                 ->first();

            //             $certificate->enroledid = $enroledRecord ? $enroledRecord->enroledid : 0;
            //             $certificate->save();
            //         });
            // }

            try 
            {
                $courses = tblcourses::where('deletedid', 0)->orderBy('coursecode', 'ASC')->get();

                $query = tblcertificatehistory::query()->with('course', 'trainee');

                $count_cert = tblcertificatehistory::all()->count();

                if (!is_null($this->selected_course)) {
                    $query->whereHas('course', function ($q) {
                        $q->where('courseid', $this->selected_course);
                    });
                }

                $searchTerm = '%' . $this->search . '%';

                $certificates = $query->whereHas('trainee', function ($q) use ($searchTerm) {
                    $q->where(function ($q) use ($searchTerm) {
                        $q->where('f_name', 'like', $searchTerm)
                            ->orWhere('m_name', 'like', $searchTerm)
                            ->orWhere('l_name', 'like', $searchTerm);
                    });
                })->orderBy('created_at', 'DESC')->paginate(12);
            } 
            catch (\Exception $e) 
            {
                $this->consoleLog($e->getMessage());
            }
      
        
        return view('livewire.admin.certificate.a-certificate-history-component',
        [
            'certificates' => $certificates,
            'courses' => $courses,
            'count_cert' => $count_cert,
        ])->layout('layouts.admin.abase');
    }
}
