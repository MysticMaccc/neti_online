<?php

namespace App\Http\Livewire\Admin\Pde;

use App\Models\tblpde;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class PdeReportHistory extends Component
{
    use WithPagination;
    use ConsoleLog;
    public $search;
    protected $paginationTheme = 'bootstrap';


    
    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes for $search
    }



    public function render()
    {
        try 
        {
            $query = tblpde::query(); 
            if (!empty($this->search)) {
                $query->where(function ($q) {
                    $q->orWhere('surname', 'like', '%' . $this->search . '%');
                    $q->orWhere('givenname', 'like', '%' . $this->search . '%');
                    $q->orWhere('middlename', 'like', '%' . $this->search . '%');
                });
            }
            $query->where('deletedid', 0);
            $pdehistory = $query->orderBy('created_at', 'asc')->paginate(20);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        
        return view('livewire.admin.pde.pde-report-history',[
            'pdehistory' =>$pdehistory
        ])->layout('layouts.admin.abase');
    }
}
