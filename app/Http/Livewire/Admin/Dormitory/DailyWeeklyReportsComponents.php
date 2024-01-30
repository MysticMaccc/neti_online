<?php

namespace App\Http\Livewire\Admin\Dormitory;

use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;

class DailyWeeklyReportsComponents extends Component
{
    use ConsoleLog;
    public $weeklydatatable = [];
    public $dailydatatable = [];
    public $dailydate  = [];
    public $company;
    public $status;
    public $paymethod;

    public function queryeverydate($date){
        try 
        {
            $datefrom = $date;
            $dailystatus = session('status');
            $companyid = session('company');
            $dailypaymethod = session('paymethod');

            if($dailystatus == 'all'){ $subquery1 = " and x.reservationstatusid NOT IN (0,3,4) "; }
            // else{ $subquery1 = " and x.reservationstatusid = ".$dailystatus." "; }
            else{ $subquery1 = " and x.reservationstatusid = ". $dailystatus; }

            if($dailypaymethod == 0){ $subquery2 = ""; }
            else{ $subquery2 = " and c.paymentmodeid = ".$dailypaymethod." "; }

            if($companyid == 0){ $subquery3 = ""; }
            else{ $subquery3 = " and b.company_id = ".$companyid." "; }

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

                return $dailydatatable = DB::select($query,[
                    'datefroma' => $datefrom,
                    'datefromb' => $datefrom
                ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function generatePdf(){

        $datefrom = session('datefrom');
        $dateto = session('dateto');
        $paymethod = session('paymethod');
        $company = session('company');
        $status = session('status');
        $type = session('type');
        $datestart = $datefrom;


    
        if ($type == "daily") {

                
                $this->paymethod = $paymethod;
                $this->company = $company;
                $this->status = $status;
                
                $dailydate = []; // Initialize an array to store dates
                $dailydatatable = []; // Initialize an array to store queried data

                // Assuming $datefrom is a string initially
                $datefrom = new DateTime($datefrom);
                $dateto = new DateTime($dateto);

                while ($datefrom <= $dateto) {
                    $dailydate[] = $datefrom->format('Y-m-d'); // Format the date as 'YYYY-MM-DD'
                    $dailydatatable[] = $this->queryeverydate($datefrom->format('Y-m-d'));
                    $datefrom->modify('+1 day'); // Increment the date by one day
                }

                
                $name = date("l, F d, Y", strtotime($datestart)).'-'.$dateto->format('l, F d, Y');
                    

                $pdf = PDF::loadView('livewire.admin.dormitory.daily-weekly-reports-components', [ 
                    'type' => $type,
                    'datestart' => $datestart,
                    'dateto' => $dateto,
                    'status' => $status,
                    'dailydate' => $dailydate,
                    'dailydatatable' => $dailydatatable,
                    'totalroomrate' => null,
                    'totalmealrate' => null,
                    'total' => null,
                    'name' => $name
                ]);
                
                $pdf->setPaper('a4', 'landscape');
                $pdf->setOption('filename', 'Daily_Report('.$name.').pdf');
                // return $pdf->stream();
                // return $pdf->download();

                return response($pdf->output(), 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="Daily_Report('.$name.').pdf"');
            
        }
        else{
            
                // $name = date("l, F d, Y", strtotime($datestart)).'-'.$dateto->format('l, F d, Y');
                $name = $datefrom->format('l, F d, Y').' - '.$dateto->format('l, F d, Y');
                
                $weeklystatus = session('status');
                $weeklycompany = session('company');
                $weeklypaymethod = session('paymethod');
        
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

                $this->weeklydatatable = DB::select($query,[
                    'dateto' => $dateto,
                    'datefrom' => $datefrom
                ]);
            } catch (Exception $e) {
                // Code to handle the exception
                echo "Something Wrong " . $e->getMessage();
            }
    
    
            

        $pdf = PDF::loadView('livewire.admin.dormitory.daily-weekly-reports-components', [ 
            'type' => $type,
            'datefrom' => $datefrom,
            'dateto' => $dateto,
            'status' => $status,
            'weeklydatatable' => $this->weeklydatatable,
            'totalroomrate' => null,
            'totalmealrate' => null,
            'total' => null,
            'name' => $name
        ]);

            $pdf->setPaper('a4', 'landscape');
            $pdf->setOption('filename', 'Weekly_Report('.$name.').pdf');
            // return $pdf->stream();
            // return $pdf->download();

            return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="Weekly_Report('.$name.').pdf"');
        
        }
    }

    public function render()
    {
        return view('livewire.admin.dormitory.daily-weekly-reports-components');
    }
}
