<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tbldormitoryreservation;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class DormitoryCheckOutComponent extends Component
{
    use AuthorizesRequests;
    public $datefrom;
    public $dateto;
    public $idtocheckout = 0;
    public $checkin;
    public $checkoutdate;
    public $reservations;
    public $listeners = ['gonoshow', 'checkout'];

    public $reservationsid;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 49);
        session()->forget('datefrom');
        session()->forget('dateto');
    }

    public function searchcheckin()
    {
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);

        $condition = " and y.startdateformat BETWEEN '".$datefrom."' AND '".$dateto."'";
        $statusquery = " x.reservationstatusid = 1  ";


        $query = "SELECT
                    a.l_name, a.f_name, a.m_name, a.suffix, a.contact_num, a.company_id,
                    b.rank,
                    c.paymentmode,
                    d.roomtype, d.nmcroomprice, d.nmcmealprice, d.mandatoryroomprice, d.mandatorymealprice,
                    e.roomname,
                    f.checkindate, f.checkoutdate, f.coursetypeid, f.id, f.datefrom, f.dateto, f.isMealActive, CONCAT(f.id) AS dormitoryreservationid,
                    f.NonNykRoomPrice, f.NonNykMealPrice, f.checkintime, f.checkouttime,
                    g.status, CONCAT(g.id) AS statusid,
                    h.company,
                    i.coursename, i.coursecode,
                    x.enroledid, y.startdateformat, y.enddateformat
                FROM
                    tbltraineeaccount AS a
                INNER JOIN tblrank AS b ON a.rank_id = b.rankid
                INNER JOIN tblenroled AS x ON x.traineeid = a.traineeid
                INNER JOIN tbldormitoryreservation AS f ON f.enroledid = x.enroledid
                INNER JOIN tblpaymentmode AS c ON c.paymentmodeid = f.paymentmodeid
                INNER JOIN tblroomname AS e ON e.id = f.roomid
                INNER JOIN tblroomtype AS d ON d.id = e.roomtypeid
                INNER JOIN tblreservationstatus AS g ON g.id = x.reservationstatusid
                INNER JOIN tblcompany AS h ON h.companyid = a.company_id
                INNER JOIN tblcourseschedule AS y ON y.scheduleid = x.scheduleid
                INNER JOIN tblcourses AS i ON i.courseid = y.courseid
                WHERE
                ".$statusquery."
                    ".$condition;

        $this->reservations = DB::select($query);


    }

    public function noshow($enrolid){
        $this->dispatchBrowserEvent('confirmation1',[
            'text' => 'Are you sure?',
            'funct' => 'gonoshow',
            'id' => $enrolid
        ]);

    }

    public function gonoshow($enrolid){
        $query = "update tblenroled set reservationstatusid = 4 where enroledid = ".$enrolid." ";
        DB::update($query);

        $this->getdata();

        $this->dispatchBrowserEvent('danielsweetalert',[
            'position' => 'middle',
            'icon' => 'success',
            'title' => 'Update successfully',
            'confirmbtn' => false

        ]);
    }

    public function resetdate(){
        session()->forget('datefrom');
        session()->forget('dateto');

        $this->getdata();
    }

    // public function confirmcheckout($enroledid){
    //     $this->dispatchBrowserEvent('confirmation1',[
    //         'text' => 'This trainee will check out if you click confirm.',
    //         'funct' => 'checkout',
    //         'id' => $enroledid
    //     ]);
    // }

    public function showcheckoutmodal($enroledid){
        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#checkoutmodal',
            'do' => 'show'
        ]);

        $this->idtocheckout = $enroledid;
    }

    public function checkout(){
        $currentPhilippinesTime = Carbon::now('Asia/Manila')->format('H:i:s');

        $query = "update tblenroled set reservationstatusid = 2   where enroledid = ".$this->idtocheckout." ";
        DB::update($query);

		$query2 = "update tbldormitoryreservation set checkoutdate = '".$this->checkoutdate."' , checkouttime = '".$currentPhilippinesTime."' where enroledid = ".$this->idtocheckout." ";
        DB::update($query2);

        $this->dispatchBrowserEvent('d_modal',[
            'id' => '#checkoutmodal',
            'do' => 'hide'
        ]);

        $this->dispatchBrowserEvent('prompt', [
            'position' => 'right',
            'icon' => 'success',
            'title' => 'Check out done',
            'confirmbtn' => true,
            'confirmbtntxt' => 'Okay',
            'time' => false
        ]);

    }


    public function getdata(){
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);

        $condition = " and y.startdateformat BETWEEN '".$datefrom."' AND '".$dateto."'";
        $statusquery = "x.reservationstatusid = 1 ";

        $query = "SELECT
                    a.l_name, a.f_name, a.m_name, a.suffix, a.contact_num, a.company_id,
                    b.rank,
                    c.paymentmode,
                    d.roomtype, d.nmcroomprice, d.nmcmealprice, d.mandatoryroomprice, d.mandatorymealprice,
                    e.roomname,
                    f.checkindate, f.checkoutdate, f.coursetypeid, f.id, f.datefrom, f.dateto, f.isMealActive, CONCAT(f.id) AS dormitoryreservationid,
                    f.NonNykRoomPrice, f.NonNykMealPrice, f.checkintime, f.checkouttime,
                    g.status, CONCAT(g.id) AS statusid,
                    h.company,
                    i.coursename, i.coursecode,
                    x.enroledid, y.startdateformat, y.enddateformat
                FROM
                    tbltraineeaccount AS a
                INNER JOIN tblrank AS b ON a.rank_id = b.rankid
                INNER JOIN tblenroled AS x ON x.traineeid = a.traineeid
                INNER JOIN tbldormitoryreservation AS f ON f.enroledid = x.enroledid
                INNER JOIN tblpaymentmode AS c ON c.paymentmodeid = f.paymentmodeid
                INNER JOIN tblroomname AS e ON e.id = f.roomid
                INNER JOIN tblroomtype AS d ON d.id = e.roomtypeid
                INNER JOIN tblreservationstatus AS g ON g.id = x.reservationstatusid
                INNER JOIN tblcompany AS h ON h.companyid = a.company_id
                INNER JOIN tblcourseschedule AS y ON y.scheduleid = x.scheduleid
                INNER JOIN tblcourses AS i ON i.courseid = y.courseid
                WHERE
                ".$statusquery."
                    ".$condition;

        $this->reservations = DB::select($query);
    }

    public function render()
    {
        if (session()->has('datefrom')) {

            $this->getdata();
        }
        return view('livewire.dormitory.dormitory-check-out-component')->layout('layouts.admin.abase');
    }
}
