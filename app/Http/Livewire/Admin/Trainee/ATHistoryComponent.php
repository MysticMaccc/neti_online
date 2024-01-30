<?php

namespace App\Http\Livewire\Admin\Trainee;

use App\Models\tblenroled;
use App\Models\tbltraineeaccount;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class ATHistoryComponent extends Component
{
    use ConsoleLog;
    public $trainee_id;
    public function mount($traineeid)
    {
        $this->trainee_id = $traineeid;
    }
    public function render()
    {
            $trainee = tbltraineeaccount::find($this->trainee_id);
            $view_enroled = tblenroled::where('traineeid', $trainee->traineeid)->get();
            $total_pending = tblenroled::where('traineeid', $trainee->traineeid)->where('pendingid', 1)->count();
            $total_enroll = tblenroled::where('traineeid', $trainee->traineeid)->where('pendingid', 0)->count();

        return view('livewire.admin.trainee.a-t-history-component',
        [
            'trainee' => $trainee,
            'view_enroled' => $view_enroled,
            'total_pending' => $total_pending,
            'total_enroll' => $total_enroll,
        ])->layout('layouts.admin.abase');
    }
}
