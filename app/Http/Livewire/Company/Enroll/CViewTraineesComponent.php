<?php

namespace App\Http\Livewire\Company\Enroll;

use App\Models\tbltraineeaccount;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class CViewTraineesComponent extends Component
{
    use WithPagination;
    public $search;

    public function render()
    {
        $query = tbltraineeaccount::where('company_id', Auth::user()->company_id);

        if ($this->search) {
            $query->where(function ($query) {
                $query->where('f_name', 'LIKE', '%' . $this->search . '%')
                    ->orWhere('l_name', 'LIKE', '%' . $this->search . '%');
            });
        }

        $t_accounts = $query->orderBy('l_name')->paginate(10);
        $c_trainees = $query->count();

        return view('livewire.company.enroll.c-view-trainees-component', [
            't_accounts' => $t_accounts,
            'c_trainees' => $c_trainees
        ])->layout('layouts.admin.abase');
    }
}
