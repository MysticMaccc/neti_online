<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tblcompany;
use App\Models\tblpaymentmode;
use App\Models\tblreservationstatus;
use Livewire\Component;
use \DateTime;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class DormitoryRoomAssignmentDailyWeeklyReportComponent extends Component
{
    use AuthorizesRequests;
    public $dailydatatable = [];
    public $weeklydatatable = [];

    public $urldata;

    public $overalltotallodgingrate = 0;
    public $overalltotalmealrate = 0;
    public $overalltotal = 0;
    public $totallodgingrate = 0;
    public $totalmealrate = 0;
    public $totalall = 0;

    public $totallodgingrateweekly;
    public $totalmealrateweekly;

    public $dailydatefrom;
    public $dailydateto;
    public $dailystatus = 'all';
    public $dailycompany = 0;
    public $dailypaymethod = 0;
    public $dailytable = false;
    public $daycount;
    public $dailydate = [];

    public $paymentmode = [];
    public $company = [];
    public $reservationstatus = [];

    public $weeklydatefrom;
    public $weeklydateto;
    public $weeklystatus = 'all';
    public $weeklycompany;
    public $selectweeklycompany;
    public $weeklypaymethod;
    public $selectpaymethod;
    public $weeklytable = false;
    public $counteddays = 0;

    public function mount(){
        Gate::authorize('authorizeAdminComponents', 51);
        $this->paymentmode = tblpaymentmode::get();
        $this->company = tblcompany::orderBy('company', 'ASC')->get();
        $this->reservationstatus = tblreservationstatus::whereIn('id', [1, 2, 4])->get();

        $this->selectpaymethod = tblpaymentmode::get();
        $this->selectweeklycompany = tblcompany::orderBy('company', 'ASC')->get();

        

        $this->totallodgingrate = 0;
    }


    //start of daily function
    //start of daily function
    //start of daily function
    //start of daily function

    public function getdailydata($date){
        // dd($date);
        $datefrom = $date;
        $dailystatus = $this->dailystatus;
        $companyid = $this->dailycompany;
        $dailypaymethod = $this->dailypaymethod;

        if($dailystatus == 'all'){ $subquery1 = " and x.reservationstatusid != 0 and x.reservationstatusid NOT IN (3,4)"; }
        // else{ $subquery1 = " and x.reservationstatusid = ".$dailystatus." "; }
        else{ $subquery1 = " and x.reservationstatusid = ". $dailystatus ." and x.reservationstatusid NOT IN (0,3,4)"; }

        if($dailypaymethod == 0){ $subquery2 = ""; }
        else{ $subquery2 = " and c.paymentmodeid = ".$dailypaymethod." "; }

        if($companyid == 0){ $subquery3 = ""; }
        else{ $subquery3 = " and b.company_id = ".$companyid." "; }

        try {
            $query =  " SELECT
            a.roomtype,
            a.nmcroomprice,
            a.nmcmealprice,
            a.mandatoryroomprice,
            a.mandatorymealprice,
            b.l_name,
            b.f_name,
            b.m_name,
            b.suffix,
            b.company_id,
            c.checkindate,
            c.checkoutdate,
            c.remarks,
            SUM(c.NonNykRoomPrice) AS totallodgingrate,
            c.NonNykRoomPrice,
            c.NonNykMealPrice,
            c.datefrom,
            c.dateto,
            d.company,
            e.paymentmode,
            f.rank,
            g.coursename,
            g.coursetypeid,
            y.startdateformat,
            y.enddateformat,
            z.roomname,
            h.status
        FROM
            tbltraineeaccount AS b
        INNER JOIN tblenroled AS x ON b.traineeid = x.traineeid
        INNER JOIN tbldormitoryreservation AS c ON c.enroledid = x.enroledid
        INNER JOIN tblcompany AS d ON d.companyid = b.company_id
        INNER JOIN tblpaymentmode AS e ON e.paymentmodeid = c.paymentmodeid
        INNER JOIN tblrank AS f ON f.rankid = b.rank_id
        INNER JOIN tblcourseschedule AS y ON y.scheduleid = x.scheduleid
        INNER JOIN tblcourses AS g ON g.courseid = y.courseid
        INNER JOIN tblroomname AS z ON z.id = c.roomid
        INNER JOIN tblroomtype AS a ON a.id = z.roomtypeid
        INNER JOIN tblreservationstatus AS h ON h.id = x.reservationstatusid
        WHERE
            :datefroma >= c.checkindate AND :datefromb <= c.checkoutdate ".$subquery1." ".$subquery2." ".$subquery3."
        GROUP BY
            a.roomtype,
            a.nmcroomprice,
            a.nmcmealprice,
            a.mandatoryroomprice,
            a.mandatorymealprice,
            b.l_name,
            b.f_name,
            b.m_name,
            b.suffix,
            b.company_id,
            c.checkindate,
            c.checkoutdate,
            c.remarks,
            c.NonNykRoomPrice,
            c.NonNykMealPrice,
            c.datefrom,
            c.dateto,
            d.company,
            e.paymentmode,
            f.rank,
            g.coursename,
            g.coursetypeid,
            y.startdateformat,
            y.enddateformat,
            z.roomname,
            h.status;";

            return $this->dailydatatable = DB::select($query,[
                'datefroma' => $datefrom,
                'datefromb' => $datefrom
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }


    }


    public function dailysearch()
    {
        $this->weeklytable = false;
        $this->weeklydatatable = [];
        
        $datefrom = new DateTime($this->dailydatefrom);
        $dateto = new DateTime($this->dailydateto);

        
        session([
            'datefrom' => $this->dailydatefrom,
            'dateto' => $this->dailydateto,
            'paymethod' => $this->dailypaymethod,
            'company' => $this->dailycompany,
            'status' => $this->dailystatus,
            'type' => 'daily'
        ]);


        // $this->urldata = null;
        // $this->urldata = base64_encode(serialize([$datefrom, $dateto, $this->dailypaymethod, $this->dailycompany, $this->dailystatus, 'daily']));

        $this->dailydate = []; // Initialize an array to store dates

        while ($datefrom <= $dateto) {
            $this->dailydate[] = $datefrom->format('Y-m-d'); // Format the date as 'YYYY-MM-DD'
            $datefrom->modify('+1 day'); // Increment the date by one day
        }

        $this->dailytable = true;
        $this->dailydatefrom = null;
        $this->dailydateto = null;
        // $this->dailycompany = 0;
        // $this->dailypaymethod = 0;
    }

    //end of daily function
    //end of daily function
    //end of daily function
    //end of daily function

    public function getweeklydata($date,$dateto){

        $this->weeklydatatable = [];

        $datefrom = $date;
        $dateto = $dateto;

        $weeklystatus = $this->weeklystatus;
        $weeklycompany = $this->weeklycompany;
        $weeklypaymethod = $this->weeklypaymethod;

        if($weeklystatus == 'all'){ $subquery1 = " and x.reservationstatusid != 0 and x.reservationstatusid != 3 and x.reservationstatusid != 4 "; }
        // else{ $subquery1 = " and x.reservationstatusid = ".$dailystatus." "; }
        else{ $subquery1 = " and x.reservationstatusid != 0 and x.reservationstatusid = ".$weeklystatus." and x.reservationstatusid != 3 and x.reservationstatusid != 4 "; }

        if($weeklypaymethod == 0){ $subquery2 = ""; }
        else{ $subquery2 = " and c.paymentmodeid = ".$weeklypaymethod." "; }

        if($weeklycompany == 0){ $subquery3 = ""; }
        else{ $subquery3 = " and b.company_id = ".$weeklycompany." "; }


        try {
            $query =  " SELECT
            a.roomtype,
            a.nmcroomprice,
            a.nmcmealprice,
            a.mandatoryroomprice,
            a.mandatorymealprice,
            b.l_name,
            b.f_name,
            b.m_name,
            b.suffix,
            b.company_id,
            c.checkindate,
            c.checkoutdate,
            c.remarks,
            SUM(c.NonNykRoomPrice) AS totallodgingrate,
            c.NonNykRoomPrice,
            c.NonNykMealPrice,
            c.datefrom,
            c.dateto,
            d.company,
            e.paymentmode,
            f.rank,
            g.coursename,
            g.coursetypeid,
            y.startdateformat,
            y.enddateformat,
            z.roomname,
            h.status
        FROM
            tbltraineeaccount AS b
        INNER JOIN tblenroled AS x ON b.traineeid = x.traineeid
        INNER JOIN tbldormitoryreservation AS c ON c.enroledid = x.enroledid
        INNER JOIN tblcompany AS d ON d.companyid = b.company_id
        INNER JOIN tblpaymentmode AS e ON e.paymentmodeid = c.paymentmodeid
        INNER JOIN tblrank AS f ON f.rankid = b.rank_id
        INNER JOIN tblcourseschedule AS y ON y.scheduleid = x.scheduleid
        INNER JOIN tblcourses AS g ON g.courseid = y.courseid
        INNER JOIN tblroomname AS z ON z.id = c.roomid
        INNER JOIN tblroomtype AS a ON a.id = z.roomtypeid
        INNER JOIN tblreservationstatus AS h ON h.id = x.reservationstatusid
        WHERE
            c.checkindate  <= :dateto AND c.checkoutdate >= :datefrom ".$subquery1." ".$subquery2." ".$subquery3."
        GROUP BY
            a.roomtype,
            a.nmcroomprice,
            a.nmcmealprice,
            a.mandatoryroomprice,
            a.mandatorymealprice,
            b.l_name,
            b.f_name,
            b.m_name,
            b.suffix,
            b.company_id,
            c.checkindate,
            c.checkoutdate,
            c.remarks,
            c.NonNykRoomPrice,
            c.NonNykMealPrice,
            c.datefrom,
            c.dateto,
            d.company,
            e.paymentmode,
            f.rank,
            g.coursename,
            g.coursetypeid,
            y.startdateformat,
            y.enddateformat,
            z.roomname,
            h.status;";

            return $this->weeklydatatable = DB::select($query,[
                'dateto' => $dateto,
                'datefrom' => $datefrom
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function weeklysearch(){
        $this->urldata = null;
        
        $datefrom = new DateTime($this->weeklydatefrom);
        $dateto = new DateTime($this->weeklydateto);

        session([
            'datefrom' => $datefrom,
            'dateto' => $dateto,
            'paymethod' => $this->weeklypaymethod,
            'company' => $this->weeklycompany,
            'status' => $this->weeklystatus,
            'type' => 'weekly'
        ]);
        
        // $this->urldata = base64_encode(serialize([$datefrom, $dateto, $this->weeklypaymethod, $this->weeklycompany, $this->weeklystatus, 'weekly']));
        
        // $this->counteddays = 0;
        // while ($datefrom <= $dateto) {
        //     $datefrom->modify('+1 day'); // Increment the date by one day
        //     $this->counteddays++;
        // }

        $this->weeklytable = true;
        $this->dailytable = false;
        $this->dailydatatable = [];
        $this->weeklydatefrom = null;
        $this->weeklydateto = null;


        $this->getweeklydata($datefrom,$dateto);
    }


    public function render()
    {
           return view('livewire.dormitory.dormitory-room-assignment-daily-weekly-report-component')->layout('layouts.admin.abase');
    }
}
