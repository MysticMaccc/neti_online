<?php

namespace App\Http\Livewire\Dormitory;

use App\Models\tblenroled;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class DormitoryReservedComponent extends Component
{
    use AuthorizesRequests;
    public $togglebatch = false;
    public $checkall = false;
    public $datefrom;
    public $dateto;
    public $coursename;
    public $meal;
    public $roomrate;
    public $traineeid;
    public $availmeal = true;
    public $fname;
    public $mname;
    public $lname;
    public $remarks;
    public $enroledid;
    public $companyid;
    public $suffix;
    public $enablereserved = 0;
    
    public $checkboxtd = [];
    public $roomtype = [];
    public $roomdata = [];
    public $coursetype = [];

    public $selectedroomtype = null;
    public $selectedrooms = null;
    public $selectedcoursetype = null;
    public $selectedpaymentmethod = null;
    
    public $noshowbatch;
    public $company;
    public $paymentmethod;
    public $data;
    public $checkin;
    public $reservations;
    public $listeners = ['cancelgo', 'noshowgo', 'noshowbatchgo', 'cancelbatchgo'];

    public $reservationsid;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 48);
        session()->forget('datefrom');
        session()->forget('dateto');
    }

    public function resetdate(){

        session()->forget('datefrom');
        session()->forget('dateto');

        $this->getdata();

        
    }

    public function reserve($enrolid){

        $query = "select
          a.f_name,a.m_name,a.l_name,a.suffix,a.traineeid AS TRID,
		  b.startdateformat,b.enddateformat,b.dateonsitefrom,b.dateonsiteto,
		  c.coursecode,c.coursename,
		  d.paymentmodeid,d.paymentmode,
          e.companyid,e.company,
          f.rankacronym, x.enroledid
		  from
		  tbltraineeaccount as a inner join tblenroled as x
		  on a.traineeid=x.traineeid
		  inner join tblcourseschedule as b
		  on b.scheduleid=x.scheduleid
		  inner join tblcourses as c
		  on c.courseid=b.courseid
		  inner join tblpaymentmode as d
		  on d.paymentmodeid=x.paymentmodeid
		  inner join tblcompany as e
		  on e.companyid=a.company_id
      inner join tblrank as f
      on f.rankid=a.rank_id
		  where
		  x.enroledid = ".$enrolid." ";

        $enroleddata = DB::select($query);

        $this->roomdata = null;
        $this->selectedrooms = null;
        $this->selectedroomtype = null;
        $this->remarks = null;
        $this->meal = null;
        $this->roomrate = null;

        $this->enroledid = $enroleddata[0]->enroledid;
        $this->traineeid = $enroleddata[0]->TRID;
        $this->fname = $enroleddata[0]->f_name;
        $this->mname = $enroleddata[0]->m_name;
        $this->lname = $enroleddata[0]->l_name;
        $this->suffix = $enroleddata[0]->suffix;
        $this->datefrom = $enroleddata[0]->startdateformat;
        $this->dateto = $enroleddata[0]->enddateformat;
        $this->coursename = $enroleddata[0]->coursecode." ".$enroleddata[0]->coursename;
        $this->company = $enroleddata[0]->company;
        $this->companyid = $enroleddata[0]->companyid;
        $this->selectedpaymentmethod = $enroleddata[0]->paymentmodeid;

        $this->dispatchBrowserEvent('d_modal',[
        'id' => '#editreservation',
        'do' => 'show'
        ]);



    }

    public function reserveform(){
        $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();

        $fname = $this->fname;
        $lname = $this->lname;
        $mname = $this->mname;
        $suffix = $this->suffix;
        $datefrom = $this->datefrom;
        $roomid = $this->selectedrooms;
        $dateto = $this->dateto;
        $enroledid = $this->enroledid;
        $coursetypeid = $this->selectedcoursetype;
        $remarks = $this->remarks;
        $availmeal = $this->availmeal;
        $paymentmethodid = $this->selectedpaymentmethod;
        $reservationstatus = 3;
        $meal = $this->meal;
        $roomrate = $this->roomrate;
        $companyid = $this->companyid;
        $philippinesTime = Carbon::now('Asia/Manila');

        $formattedTime = $philippinesTime->format('H:i:s');
        $updatedat = $philippinesTime->format('Y-m-d H:i:s');

        $result1 = null; $result2 = null; $result3 = null;
        //updatecompany
        $traineeid = tblenroled::find($enroledid);
        $querycompany = "UPDATE tbltraineeaccount SET company_id = ".$companyid.", updated_at = '".$updatedat."' WHERE traineeid = ".$traineeid->traineeid."";

        // dd($traineeid->traineeid);

        //updatedormitorytbl
        // $querydormitory = "insert into tbldormitoryreservation(id,enroledid,firstname,middlename,lastname,suffix,datefrom,dateto,
		// coursetypeid,remarks,roomid,isMealActive,paymentmodeid,checkindate,checkoutdate,
		// NonNykRoomPrice,NonNykMealPrice)
		// values
		// (NULL,".$enroledid.",'".$fname."','".$mname."','".$lname."','".$suffix."','".$datefrom."','".$dateto."',
		// ".$coursetypeid.",'".$remarks."',".$roomid.",".$availmeal.",".$paymentmethodid.",'".$datefrom."','".$dateto."'
		// ,'".$roomrate."','".$meal."')";

        //updatetblenroled
        $queryenroled = "update tblenroled set reservationstatusid = :reservationstatusid where enroledid = :enroledid ";



        $errorhandler = 1;



        $result1 = DB::update($querycompany);

        if ($result1 > 0) {

        }else{
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'error',
                'title' => 'There is an error (error code #r1)',
                'confirmbtn' => false
            ]);
            $errorhandler = 0;
        }

        if ($errorhandler == 1) {
            $result2 = DB::table('tbldormitoryreservation')->insert([
                'enroledid' => $enroledid,
                'firstname' => $fname,
                'middlename' => $mname,
                'lastname' => $lname,
                'suffix' => $suffix,
                'datefrom' => $datefrom,
                'dateto' => $dateto,
                'coursetypeid' => $coursetypeid,
                'remarks' => $remarks,
                'roomid' => $roomid,
                'isMealActive' => $availmeal,
                'paymentmodeid' => $paymentmethodid,
                'checkindate' => $datefrom,
                'checkoutdate' => null,
                'NonNykRoomPrice' => $roomrate,
                'NonNykMealPrice' => $meal,
                'checkintime' => $formattedTime,
                'checkouttime' => '00:00:00',
                'is_reserved' => 1
            ]);

            if ($result2 > 0) {

            }else{
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'middle',
                    'icon' => 'error',
                    'title' => 'There is an error (error code #r2)',
                    'confirmbtn' => false
                ]);
                $errorhandler = 0;
            }


        }

        if ($errorhandler == 1) {
            $result3 = DB::update($queryenroled, [
                'reservationstatusid' => $reservationstatus,
                'enroledid' => $enroledid
            ]);

            if ($result3 > 0) {

            }else{
                $this->dispatchBrowserEvent('danielsweetalert', [
                    'position' => 'middle',
                    'icon' => 'error',
                    'title' => 'There is an error (error code #r3)',
                    'confirmbtn' => false
                ]);
                $errorhandler = 0;
            }
        }


        if ($errorhandler == 1) {
            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Reserved',
                'confirmbtn' => false
            ]);

            $this->dispatchBrowserEvent('d_modal', [
                'id' => '#editreservation',
                'do' => 'hide'
            ]);
        }

    }

    public function updatedselectedcoursetype(){
        $roomtypeid = $this->selectedroomtype;
        $coursetypeid = $this->selectedcoursetype;

        if (!empty($roomtypeid) && !empty($coursetypeid)) {
            $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->get();
            $this->selectedrooms = $this->selectedrooms;

            $roomtypedata = DB::table('tblroomtype')->find($roomtypeid);

            if ($coursetypeid == 1) {
                $this->meal = $roomtypedata->mandatorymealprice;
                $this->enablereserved = 1;
                $this->roomrate = $roomtypedata->mandatoryroomprice;
            }else{
                $this->meal = $roomtypedata->nmcmealprice;
                $this->roomrate = $roomtypedata->nmcroomprice;
                $this->enablereserved = 1;
            }
        }else{
            $this->enablereserved = 0;
        }
    }

    public function searchcheckin()
    {
        $datefrom = date('Y-m-d', strtotime($this->datefrom));
        $dateto = date('Y-m-d', strtotime($this->dateto));

        session(['datefrom' => $datefrom]);
        session(['dateto' => $dateto]);

        $query = "select
						  a.l_name,a.f_name,a.m_name,a.suffix,a.contact_num,a.email,
						  b.rankacronym, b.rank,
						  c.coursename,c.coursecode,
						  d.company,
						  e.paymentmode,
						  f.startdateformat,f.enddateformat,
						  x.enroledid
						  from
						  tbltraineeaccount as a inner join tblenroled as x
						  on a.traineeid=x.traineeid
						  inner join tblrank as b
						  on b.rankid=a.rank_id
						  inner join tblcourseschedule as f
						  on f.scheduleid=x.scheduleid
						  inner join tblcourses as c
						  on c.courseid=f.courseid
						  inner join tblcompany as d
						  on d.companyid=a.company_id
						  inner join tblpaymentmode as e
						  on e.paymentmodeid=x.paymentmodeid
						  where
						  x.reservationstatusid = 0 and x.pendingid = 0 and x.deletedid = 0 and x.dormid != 0 and
						  f.startdateformat BETWEEN '".$datefrom."' and '".$dateto."'
						  ";

        $this->reservations = DB::select($query);

    }

    public function getdata(){
        $datefrom = session('datefrom');
        $dateto = session('dateto');
        

        $query = "select
						  a.l_name,a.f_name,a.m_name,a.suffix,a.contact_num,a.email,b.rank,
						  b.rankacronym,
						  c.coursename,c.coursecode,
						  d.company,
						  e.paymentmode,
						  f.startdateformat,f.enddateformat,
						  x.enroledid
						  from
						  tbltraineeaccount as a inner join tblenroled as x
						  on a.traineeid=x.traineeid
						  inner join tblrank as b
						  on b.rankid=a.rank_id
						  inner join tblcourseschedule as f
						  on f.scheduleid=x.scheduleid
						  inner join tblcourses as c
						  on c.courseid=f.courseid
						  inner join tblcompany as d
						  on d.companyid=a.company_id
						  inner join tblpaymentmode as e
						  on e.paymentmodeid=x.paymentmodeid
						  where
						  x.reservationstatusid = 0 and x.pendingid = 0 and x.deletedid = 0 and x.dormid != 0 and
						  f.startdateformat BETWEEN '".$datefrom."' and '".$dateto."'
						  ";

        return $this->reservations = DB::select($query);
    }

    public function render()
    {

        if (session()->has('datefrom')) {

            $this->getdata();
        }

        if ($this->selectedroomtype) {
            $this->roomdata = DB::table('tblroomname')->where('roomtypeid', $this->selectedroomtype)->orderBy('roomname', 'ASC')->get();
        }

        $this->roomtype = DB::table('tblroomtype')->orderBy('roomtype', 'ASC')->get();;
        $this->coursetype = DB::table('tblcoursetype')->where('reservationdeletedid', 0)->get();
        $this->paymentmethod = DB::table('tblpaymentmode')->orderBy('paymentmode', 'ASC')->get();

        return view('livewire.dormitory.dormitory-reserved-component')->layout('layouts.admin.abase');
    }
}
