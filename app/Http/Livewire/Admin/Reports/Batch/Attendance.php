<?php

namespace App\Http\Livewire\Admin\Reports\Batch;

use App\Models\tblcourseschedule;
use App\Models\tblenroled;
use App\Models\tblremedial;
use Livewire\Component;
use App\Models\tblscheduleattendance;
use Carbon\Carbon;
use Illuminate\Validation\Rules\Enum;

class AttendanceStatus extends Enum
{
    const PRESENT_AM = 1;
    const PRESENT_PM = 1;
    const ABSENT_AM = 1;
    const ABSENT_PM = 1;
    const CANCEL_AM = 1;
    const CANCEL_PM = 1;
    const NOSHOW_AM = 1;
    const NOSHOW_PM = 1;
    const DROP_AM = 1;
    const DROP_PM = 1;
    const NO_SHOW = 6;
}



class Attendance extends Component
{
    public $training_id;
    public $schedule;
    public $traineeid;

    public $attendanceData;
    public $name;
    public $attendees = [];
    public $days = [];
    public $remedials = [];

    public function mount($training_id)
    {

        $this->schedule = tblcourseschedule::find($training_id);
    }

    public function getTrainingDays($schedule)
    {

        $startDate = Carbon::createFromFormat('Y-m-d', $schedule->startdateformat);
        $endDate = Carbon::createFromFormat('Y-m-d', $schedule->enddateformat);

        //create $arr collection 
        $arr = collect();

        while ($startDate <= $endDate) {
            // Check if the current day is not a Sunday (day of week = 0)
            if ($startDate->dayOfWeek !== 0) {
                //  $arr[$startDate->format('Y-m-d')] = $startDate->format('l');
                $date = $startDate->format('Y-m-d');
                $date_day = $startDate->format('l');


                $dayData = collect([
                    'date' => $date,
                    'date_day' => $date_day,

                ]);

                $arr->push($dayData);
            }
            $startDate->addDay();
        }

        return $arr;
    }

    public function openModal($traineeid, $scheduleid)
    {
        $attendee = tblenroled::where('traineeid', $traineeid)->where('scheduleid', $scheduleid)->first();
        $this->traineeid = $attendee->traineeid;
        $this->name = $attendee->trainee->formal_name();
    }

    public function setRemedial()
    {
        $trainee = tblenroled::where('traineeid', $this->traineeid)
            ->where('scheduleid', $this->schedule->scheduleid)
            ->first();

        if ($trainee) {
            // Check if a record already exists in tblremedial
            $existingRemedial = tblremedial::where('enroledid', $trainee->enroledid)
                ->where('scheduleid', $this->schedule->scheduleid)
                ->first();

            if (!$existingRemedial) {
                // If no record exists, create and save a new one
                $remedial = new tblremedial();
                $remedial->enroledid = $trainee->enroledid;
                $remedial->scheduleid = $this->schedule->scheduleid;
                $remedial->save();
            }

            tblenroled::where('traineeid', $this->traineeid)
                ->where('scheduleid', $this->schedule->scheduleid)
                ->update([
                    'pendingid' => 3,
                    'IsRemedial' => 1
                ]);

            // Dispatch a browser event
            $this->dispatchBrowserEvent('save-log', [
                'title' => 'Approved'
            ]);
        }
    }


    public function setFailed()
    {
        tblenroled::where('traineeid', $this->traineeid)
            ->where('scheduleid', $this->schedule->scheduleid)
            ->update([
                'passid' => 2,
            ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Approved'
        ]);
    }

    public function setPassed()
    {
        tblenroled::where('traineeid', $this->traineeid)
            ->where('scheduleid', $this->schedule->scheduleid)
            ->update([
                'passid' => 1,
                'IsRemedial' => 0,
            ]);

        $this->dispatchBrowserEvent('save-log', [
            'title' => 'Approved'
        ]);
    }


    public function updateAttendance()
    {
        foreach ($this->attendanceData as $attendeeid => $dateStatus) {
            foreach ($dateStatus as $key => $value) {
                $attributes = [
                    'scheduleid' => $this->schedule->scheduleid,
                    'traineeid' => $attendeeid,
                    'date' => $key,
                ];
    
                $this->storeAttendance($attributes, $value);
            }
        }
    }
    
    private function storeAttendance(array $attributes, array $value)
    {
        $criteria = [
            'scheduleid' => $attributes['scheduleid'],
            'traineeid' => $attributes['traineeid'],
            'date' => $attributes['date'],
        ];
    
        if (isset($value['present_am']) && $value['present_am'] == 1) {
            $attributes['present_am'] = AttendanceStatus::PRESENT_AM;
            $attributes['absent_am'] = null;
            $attributes['cancel_am'] = null;

        } else {
            $attributes['present_am'] = null;
        }
    
        if (isset($value['present_pm']) && $value['present_pm'] == 1) {
            $attributes['present_pm'] =  AttendanceStatus::PRESENT_PM;
            $attributes['absent_pm'] = null;
            $attributes['cancel_pm'] = null;

        } else {
            $attributes['present_pm'] = null;
        }

        if (isset($value['absent_am']) && $value['absent_am'] == 1) {
            $attributes['absent_am'] = AttendanceStatus::ABSENT_AM;
            $attributes['present_am'] = null;
            $attributes['cancel_am'] = null;
        } else {
            $attributes['absent_am'] = null;
        }
    
        if (isset($value['absent_pm']) && $value['absent_pm'] == 1) {
            $attributes['absent_pm'] =  AttendanceStatus::ABSENT_PM;
            $attributes['present_pm'] = null;
            $attributes['cancel_am'] = null;

        } else {
            $attributes['absent_pm'] = null;
        }

        if (isset($value['noshow_am']) && $value['noshow_am'] == 1) {
            $attributes['noshow_am'] = AttendanceStatus::NOSHOW_AM;
            $attributes['present_am'] = null;

        } else {
            $attributes['noshow_am'] = null;
        }
    
        if (isset($value['noshow_pm']) && $value['noshow_pm'] == 1) {
            $attributes['noshow_pm'] =  AttendanceStatus::NOSHOW_PM;
            $attributes['present_pm'] = null;

        } else {
            $attributes['noshow_pm'] = null;
        }

        if (isset($value['cancel_am']) && $value['cancel_am'] == 1) {
            $attributes['cancel_am'] = AttendanceStatus::CANCEL_AM;
            $attributes['present_am'] = null;
            $attributes['absent_am'] = null;

        } else {
            $attributes['cancel_am'] = null;
        }
    
        if (isset($value['cancel_pm']) && $value['cancel_pm'] == 1) {
            $attributes['cancel_pm'] =  AttendanceStatus::CANCEL_PM;
            $attributes['present_pm'] = null;
            $attributes['absent_pm'] = null;

        } else {
            $attributes['cancel_pm'] = null;
        }

        if (isset($value['drop_am']) && $value['drop_am'] == 1) {
            $attributes['drop_am'] = AttendanceStatus::DROP_AM;
            $attributes['present_am'] = null;
            $attributes['absent_am'] = null;
            $attributes['cancel_am'] = null;


        } else {
            $attributes['drop_am'] = null;
        }
    
        if (isset($value['drop_pm']) && $value['drop_pm'] == 1) {
            $attributes['drop_pm'] =  AttendanceStatus::DROP_PM;
            $attributes['present_pm'] = null;
            $attributes['absent_pm'] = null;
            $attributes['cancel_pm'] = null;

        } else {
            $attributes['drop_pm'] = null;
        }
    
        tblscheduleAttendance::updateOrCreate($criteria, $attributes);
    }
    

    public function render()
    {

        $this->attendees  = tblenroled::where(function ($query) {
            $query->where('scheduleid', $this->training_id)
                ->where(function ($subquery) {
                    $subquery->where('pendingid', 0)
                        ->orWhere('pendingid', 3);
                });
        })
            ->join('tbltraineeaccount', 'tblenroled.traineeid', '=', 'tbltraineeaccount.traineeid')
            ->select('tbltraineeaccount.traineeid', 'tbltraineeaccount.f_name', 'tbltraineeaccount.l_name', 'tblenroled.passid', 'tblenroled.pendingid')
            ->orderBy('IsRemedial', 'desc')
            ->orderBy('tbltraineeaccount.f_name', 'asc')
            ->get();

        $this->days = $this->getTrainingDays($this->schedule);

        // dd($this->days);
        $attendance  = tblscheduleattendance::whereIn('traineeid', $this->attendees->pluck('traineeid'))
            ->where('scheduleid',  $this->training_id)->get();

        $organizedData = [];

        foreach ($attendance as $record) {

            $traineeId = $record->traineeid;
            $date = $record->date;
            $present_am = $record->present_am  == null ? $record->status : $record->present_am;
            $present_pm = $record->present_pm  == null ? $record->status : $record->present_pm;
            $absent_am = $record->absent_am  == null ? $record->status : $record->absent_am;
            $absent_pm = $record->absent_pm  == null ? $record->status : $record->absent_pm;
            $noshow_am = $record->noshow_am  == null ? $record->status : $record->noshow_am;
            $noshow_pm = $record->noshow_pm  == null ? $record->status : $record->noshow_pm;
            $cancel_am = $record->cancel_am  == null ? $record->status : $record->cancel_am;
            $cancel_pm = $record->cancel_pm  == null ? $record->status : $record->cancel_pm;
            $drop_am = $record->drop_am  == null ? $record->status : $record->drop_am;
            $drop_pm = $record->drop_pm  == null ? $record->status : $record->drop_pm;


            // You can organize the data as an associative array or a collection
            // For example, using an associative array:
            $organizedData[$traineeId][$date]['present_am'] = $present_am;
            $organizedData[$traineeId][$date]['present_pm'] = $present_pm;
            $organizedData[$traineeId][$date]['absent_am'] = $absent_am;
            $organizedData[$traineeId][$date]['absent_pm'] = $absent_pm;
            $organizedData[$traineeId][$date]['noshow_am'] = $noshow_am;
            $organizedData[$traineeId][$date]['noshow_pm'] = $noshow_pm;
            $organizedData[$traineeId][$date]['cancel_am'] = $cancel_am;
            $organizedData[$traineeId][$date]['cancel_pm'] = $cancel_pm;
            $organizedData[$traineeId][$date]['drop_am'] = $drop_am;
            $organizedData[$traineeId][$date]['drop_pm'] = $drop_pm;


        }
        $this->attendanceData = $organizedData;

        foreach ($this->attendanceData as $attendeeName => $dateStatus) {
            $absentCount = collect($dateStatus)->filter(function ($status) {
                return $status == 4; // Assuming 4 represents "ABSENT"
            })->count();

            if ($absentCount >= 1) {
                // Mark the attendee as remedial
                $this->remedials[] = $attendeeName;
            }

            // Update the database with $attendeeName and $dateStatus
        }
        return view('livewire.admin.reports.batch.attendance');
    }
}
