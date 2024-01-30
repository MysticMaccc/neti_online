<?php

namespace App\Http\Livewire\Admin\Enrollment;

use App\Models\tblenroled;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AEnrollmentLogComponent extends Component
{
    use ConsoleLog;
    use AuthorizesRequests;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $search;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 46);
    }

    public function render()
    {
        try 
        {
            if ($this->search) {
                $logs = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->where(function ($query) {
                        $query->where('tbltraineeaccount.f_name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('tbltraineeaccount.l_name', 'LIKE', '%' . $this->search . '%')
                            ->orWhere('tbltraineeaccount.m_name', 'LIKE', '%' . $this->search . '%');
                    })
                    ->orderBy('tblenroled.enroledid', 'desc')
                    ->paginate(10);
            } else {
                $logs = tblenroled::join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
                    ->orderBy('tblenroled.enroledid', 'desc')
                    ->paginate(10);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.enrollment.a-enrollment-log-component',
        [
            'logs' => $logs
        ])->layout('layouts.admin.abase');
    }
}
