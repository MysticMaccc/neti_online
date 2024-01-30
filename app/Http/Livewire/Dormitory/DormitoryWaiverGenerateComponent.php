<?php

namespace App\Http\Livewire\Dormitory;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;

class DormitoryWaiverGenerateComponent extends Component
{
	use AuthorizesRequests;
    public $reservations = [];
	public $checkboxtd = [];
    public $dateto;
    public $datefrom;
	public $togglebatch = false;
    public $checkall = false;

    use WithPagination;

	public function mount()
	{
			Gate::authorize('authorizeAdminComponents', 52);
	}

    public function printwaiver($id){
        $query = "select
						  a.traineeid,a.address,a.l_name,a.f_name,a.m_name,a.suffix,a.contact_num,a.email,b.rank,
						  b.rankacronym,
						  c.coursename,c.coursecode,
						  d.company,
						  e.paymentmode,
						  f.startdateformat,f.enddateformat,
						  g.nationality,
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
						  inner join tblnationality as g on g.nationalityid = a.nationalityid
						  where a.traineeid = ".$id." and
						  x.reservationstatusid = 0 and x.pendingid = 0 and x.deletedid = 0 and x.dormid != 1 and
						  f.startdateformat BETWEEN '".session('datefrom')."' and '".session('dateto')."'
						  ";

        $data = DB::select($query);

        session(['waiverdata' => $data]);
        return redirect()->route('a.dormitorywaiverammenities');

    }

    public function getdata(){
        $datefrom = date("Y-m-d", strtotime(session('datefrom')));
        $dateto = date("Y-m-d", strtotime(session('dateto')));

        $query = "select
						  a.traineeid,a.l_name,a.f_name,a.m_name,a.suffix,a.contact_num,a.email,b.rank,
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
						  x.reservationstatusid = 0 and x.pendingid = 0 and x.deletedid = 0 and x.dormid != 1 and
						  f.startdateformat BETWEEN '".$datefrom."' and '".$dateto." order by a.f_name ASC'
						  ";

        return $this->reservations = DB::select($query);
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
						  x.reservationstatusid = 0 and x.pendingid = 0 and x.deletedid = 0 and x.dormid != 1 and
						  f.startdateformat BETWEEN '".$datefrom."' and '".$dateto." order by a.f_name ASC'
						  ";

        $this->reservations = DB::select($query);

       

    }

	public function generatewaiverbatch(){
		// Filter the checkbox data to retrieve only the 'true' values
		$trueCheckboxes = array_filter($this->checkboxtd, function($value) {
			return $value == 'true';
		});

	
		// Store the filtered 'true' checkboxes in the session
		session()->forget('waiverdata');
		session(['waiverdatabatch' => $trueCheckboxes]);
	
		return redirect()->route('a.dormitorywaiverammenities');
	}

    public function resetdate()
    {
        session()->forget('datefrom');
        session()->forget('dateto');  
    }

    public function render()
    {

        if (session()->has('datefrom')) {

            $this->getdata();
        }else{
            $this->reservations = [];
        }

		if ($this->checkall == true) {
            if ($this->reservations) {
                foreach($this->reservations as $reservations){
                    $this->checkboxtd[$reservations->traineeid] = true;
                }
            }
        }else{
            if ($this->reservations) {
                foreach($this->reservations as $reservations){
                    $this->checkboxtd[$reservations->traineeid] = false;
                }
            }
        }

        return view('livewire.dormitory.dormitory-waiver-generate-component')->layout('layouts.admin.abase');
    }
}
