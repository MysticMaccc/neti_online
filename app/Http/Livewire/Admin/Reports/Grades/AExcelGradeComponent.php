<?php

namespace App\Http\Livewire\Admin\Reports\Grades;

use App\Exports\GradeExport;
use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use Exception;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class AExcelGradeComponent extends Component
{
    public $enroledid;

    public function export()
    {
        $scheduleid = Session::get('scheduleid');
        $schedule = tblcourseschedule::find($scheduleid);
        $crews = tblenroled::where(function ($query) use ($scheduleid) {
            $query->where('scheduleid', $scheduleid)
                ->where(function ($subquery) {
                    $subquery->where('pendingid', 0)
                        ->orWhere('pendingid', 3);
                });
        })->where('IsRemedialConfirmed', 0)
        ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
        ->orderBy('IsRemedial', 'desc')
        ->orderBy('tbltraineeaccount.l_name', 'asc')
        ->get();


        $filenameExport = $schedule->course->coursecode . '(' . $schedule->startdateformat . ')' . ".xlsx";

        try {
            return Excel::download(new GradeExport([$crews, $schedule]), $filenameExport);
        } catch (Exception $e) {
            dd($e);
        }
    }
    
    public function render()
    {
        return view('livewire.admin.reports.grades.a-excel-grade-component');
    }
}
