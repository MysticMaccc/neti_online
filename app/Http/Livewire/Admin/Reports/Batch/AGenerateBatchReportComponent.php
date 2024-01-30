<?php

namespace App\Http\Livewire\Admin\Reports\Batch;

use App\Models\tblcourseschedule;
use App\Models\tblcoursetype;
use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AGenerateBatchReportComponent extends Component
{
    use ConsoleLog;
    use WithPagination;
    public $selected_batch;

    public $start_date = null;
    public $end_date = null;
    public $array = [];

    protected $rules = [
        'start_date' => 'required',
        'end_date' => 'required|after_or_equal:start_date',
    ];

    public function cutoff_all()
    {
        try {
            $trainingSchedules = tblcourseschedule::where('batchno', $this->selected_batch)->get();

            foreach ($trainingSchedules as $schedule) {
                $trainees = tblenroled::where('scheduleid', $schedule->scheduleid)->where('pendingid', 1)->get();

                // Set cutoff for the schedule
                $schedule->cutoffid = 1;
                $schedule->save();

                // Delete trainee records
                foreach ($trainees as $trainee) {
                    $trainee->delete();
                }
            }

            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Cutoff applied, and trainee records deleted in the selected batch.'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }



    public function uncutoff_all()
    {
        try {
            $training_schedules = tblcourseschedule::where('batchno',  $this->selected_batch)->get();

            foreach ($training_schedules as $schedule) {
                $schedule->cutoffid = 0;
                $schedule->save();
            }
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Cuttoff successfully applied in selected batch.'
            ]);
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        try {
            $course_type = tblcoursetype::all();
            $course_type_ids = $course_type->pluck('coursetypeid')->toArray();
            $training_schedules = tblcourseschedule::addSelect([
                'enrolled_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                    ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                    ->where('tblenroled.pendingid', 0),
                'slot_pending_count' => tblenroled::select(DB::raw('COUNT(*)'))
                    ->whereColumn('tblcourseschedule.scheduleid', 'tblenroled.scheduleid')
                    ->where('tblenroled.pendingid', 1),
            ])->whereHas('course', function ($query) use ($course_type_ids) {
                $query->whereIn('coursetypeid', $course_type_ids);
            })
                ->join('tblcourses', 'tblcourses.courseid', '=', 'tblcourseschedule.courseid')
                ->where('batchno', $this->selected_batch)
                ->orderBy('tblcourses.coursename', 'ASC')
                ->orderBy('tblcourses.modeofdeliveryid', 'ASC')
                ->orderBy('startdateformat', 'ASC')
                ->paginate(20);

            $currentYear = Carbon::now()->year;
            $batchWeeks = tblcourseschedule::select('batchno')
                ->where('startdateformat', 'like', '%' . $currentYear . '%')
                ->groupBy('batchno')
                ->orderBy('startdateformat','asc')
                ->get();
        } catch (\Exception $e) {
            $this->consoleLog($e->getMessage());
        }


        return view(
            'livewire.admin.reports.batch.a-generate-batch-report-component',
            [
                'training_schedules' => $training_schedules,
                'batchWeeks' => $batchWeeks,
            ]
        )->layout('layouts.admin.abase');
    }
}
