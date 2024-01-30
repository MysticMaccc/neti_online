<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblscheduleattendance;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;
use Livewire\Component;

class AGenerateAttendanceComponent extends Component
{
    public $scheduleid;
    public $att_trainees;
    public $dateRange = [];
    public $attendanceData;

    public function generatePdf($scheduleid)
    {
        // $this->att_trainees = tblenroled::where('scheduleid', $scheduleid)->where('pendingid', 0)->orWhere('pendingid', 3)   
        // ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
        // ->orderBy('IsRemedial', 'desc')
        // ->orderBy('tbltraineeaccount.l_name', 'asc')
        // ->get();
        
        $this->att_trainees = tblenroled::where(function ($query) use ($scheduleid) {
            $query->where('scheduleid', $scheduleid)
                ->where(function ($subquery) {
                    $subquery->where('pendingid', 0)
                        ->orWhere('pendingid', 3);
                });
        })
        ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
        ->orderBy('IsRemedial', 'desc')
        ->orderBy('tbltraineeaccount.l_name', 'asc')
        ->get();

        if($this->att_trainees->isEmpty())
        {
            abort(404);
        }
        
        $schedule = tblcourseschedule::findOrFail($scheduleid);


        $startDate = Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $endDate = Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        while ($startDate <= $endDate) {
            // Check if the current day is not a Sunday (day of week = 0)
            if ($startDate->dayOfWeek !== 0) {
                $this->dateRange[$startDate->format('Y-m-d')] = $startDate->format('l');
            }
            $startDate->addDay();
        }

        $this->attendanceData = tblscheduleattendance::whereIn('traineeid',  $this->att_trainees->pluck('traineeid'))
            ->whereIn('date', array_keys($this->dateRange))
            ->where('scheduleid', $scheduleid)
            ->get();

        $data = [
            'schedule' => $schedule,
            'att_trainees' => $this->att_trainees,
            'dateRange' => $this->dateRange,
            'attendanceData' => $this->attendanceData,
        ];
        $pdf = FacadePdf::loadView('livewire.admin.generate-docs.a-generate-attendance-component', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-attendance-component');
    }
}
