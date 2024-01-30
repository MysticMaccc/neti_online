<?php

namespace App\Http\Livewire\Admin\Instructor;

use App\Models\tblcourseschedule;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class InstructorHistoryComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    public $search;
    public $datefrom;
    public $dateto;

    protected $rules = [
        'datefrom' => 'required|date|before:dateto',
        'dateto' => 'required|date|after:datefrom',
    ];

    protected $messages = [
        'datefrom.before' => 'Date from must be less than date to',
        'dateto.after' => 'Date to must be greater than date from'
    ];

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes
    }

    public function exportinstructorhistory(){
        $this->validate();

        session(['datefrom' => $this->datefrom]);
        session(['dateto' => $this->dateto]);

        return redirect()->route('a.reportinstructorhistory');
    }


    public function render()
    {
        try 
        {
            $query = tblcourseschedule::where('deletedid', 0);

            if (!empty($this->search)) {

                $query->whereHas('instructor.user', function ($subquery) {
                    $subquery->where('f_name', 'like', '%' . $this->search . '%')
                        ->orWhere('m_name', 'like', '%' . $this->search . '%')
                        ->orWhere('l_name', 'like', '%' . $this->search . '%');
                });

                $query->orwhereHas('instructor.rank', function ($subquery) {
                    $subquery->where('rankacronym', 'like', '%' . $this->search . '%')
                        ->orWhere('rank', 'like', '%' . $this->search . '%');
                });

                $query->orwhereHas('course', function ($subquery) {
                    $subquery->where('coursecode', 'like', '%' . $this->search . '%')
                        ->orWhere('coursename', 'like', '%' . $this->search . '%');
                });

                $query->orWhere('batchno', 'like', '%' . $this->search . '%')
                    ->orWhere('start', 'like', '%' . $this->search . '%')
                    ->orWhere('end', 'like', '%' . $this->search . '%');
            }

            $i_accounts = $query->where('instructorid', '!=', 93)
                ->orderBy('startdateformat', 'DESC')
                ->paginate(10);

        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.instructor.instructor-history-component', [
            'i_accounts' => $i_accounts
        ])->layout('layouts.admin.abase');
    }
}
