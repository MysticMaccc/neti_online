<?php

namespace App\Http\Livewire\Admin\Instructor;

use App\Models\tblinstructor;
use App\Models\tblrank;
use App\Models\user;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\EventDispatcher\EventDispatcher;

use function Laravel\Prompts\select;

class IInstructorComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';
    public $search = "";
    public $searchinactive = "";
    public $instructorid;
    public $password = 'instructor';
    protected $listeners = ['deactivatesql', 'tagregularsql', 'removeregularsql', 'activateinsquery'];


    protected $passwordhash;
    protected $hashid;
    public $rankid;
    public $email;
    public $lastname;
    public $middlename;
    public $firstname;
    public $userid;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 8);
    }

    public function addinstructor(){
        try 
        {
            $query = user::orderBy('user_id', 'DESC')->first();
            $userid = $query->user_id;
            $userid++;
            $rankid = $this->rankid;

            $hashid = hash('sha256', $userid);
            $passwordhash = Hash::make($this->password);
            //add new user profile

            $this->validate([
                'firstname' => 'required',
                'middlename' => 'required',
                'lastname' => 'required',
                'email' => 'required|email',
                // 'password' => 'required|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
                'password' => 'required|min:8',
                'rankid' => 'required'
            ]);

            user::create([
                'l_name' => $this->lastname,
                'hash_id' => $hashid,
                'm_name' => $this->middlename,
                'f_name' => $this->firstname,
                'email' => $this->email,
                'password_tip' => $this->password,
                'user_id' => $userid,
                'password' => $passwordhash,
                'is_active' => 1,
                'u_type' => 2
            ]);

            tblinstructor::create([
                'userid' => $userid,
                'rankid' => $rankid,
                'is_Deleted' => 0,
                'regularid' => 0
            ]);

            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Instructor successfully created',
                'confirmbtn' => 'Ok'
            ]);

            $this->dispatchBrowserEvent('d_modal',[
                'id' => '#addinstructormodal',
                'do' => 'hide'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }


    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination to the first page when the search query changes
    }




    public function deactivatesql($id){
        try 
        {
            $instructorid = $id;

            $query = tblinstructor::where('instructorid', $instructorid)->first();

            if ($query) {
                $query->update([
                    'is_Deleted' => 1
                ]);
            }

            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Deactivate complete',
                'confirmbtn' => false
            ]);

            return redirect()->route('a.instructor');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function deactivate($id,$name)
    {
        $this->dispatchBrowserEvent('confirmation1',[
            'text' => 'You will deactivating the account of '.$name,
            'id' => $id,
            'funct' => 'deactivatesql'
        ]);

    }

    public function tagregular($id){
        $this->dispatchBrowserEvent('confirmation1',[
            'text' => 'Do you want to tag this instructor as regular?',
            'id' => $id,
            'funct' => 'tagregularsql'
        ]);
    }

    public function removeregular($id){
        $this->dispatchBrowserEvent('confirmation1',[
            'text' => 'Do you want to remove this instructor as regular?',
            'id' => $id,
            'funct' => 'removeregularsql'
        ]);
    }

    public function removeregularsql($id){
        try 
        {
            $instructorid = $id;

            $query = tblinstructor::where('instructorid', $instructorid)->first();
            if ($query) {
                $query->update([
                    'regularid' => 0
                ]);
            }
    
            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Done',
                'confirmbtn' => false
            ]);  
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
    }

    public function tagregularsql($id){
        try 
        {
            $instructorid = $id;

            $query = tblinstructor::where('instructorid', $instructorid)->first();
            if ($query) {
                $query->update([
                    'regularid' => 1
                ]);
            }

            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Done',
                'confirmbtn' => false
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function activateins($insid){
        $this->dispatchBrowserEvent('confirmation1', [
            'text' => 'Are you sure you want to activate?',
            'funct' => 'activateinsquery',
            'id' => $insid
        ]);
        // $insdata = tblinstructor::find($insid)->first();

    }

    public function activateinsquery($insid){
        try 
        {
            $insdata = tblinstructor::find($insid);
            $insdata->is_Deleted = 0;
            $insdata->save();

            $this->dispatchBrowserEvent('danielsweetalert', [
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Activated',
                'confirmbtn' => false
            ]);

            //add code close modal
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function render()
    {
        try 
        {
            $query = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid');

            if (!empty($this->search)) {
                $query->orWhere('f_name', 'like', '%'.$this->search.'%')
                ->orWhere('m_name', 'like', '%'.$this->search.'%')
                ->orWhere('l_name', 'like', '%'.$this->search.'%')
                ->orWhere('tblrank.rank', 'like', '%'.$this->search.'%');
            }

            $queryinactive = tblinstructor::join('users', 'tblinstructor.userid', '=', 'users.user_id')
            ->join('tblrank', 'tblinstructor.rankid', '=', 'tblrank.rankid')->where('is_Deleted', 1);

            if (!empty($this->searchinactive)) {
                $queryinactive->orWhere('f_name', 'like', '%'.$this->searchinactive.'%')
                ->orWhere('m_name', 'like', '%'.$this->searchinactive.'%')
                ->orWhere('l_name', 'like', '%'.$this->searchinactive.'%')
                ->orWhere('rank', 'like', '%'.$this->searchinactive.'%');
            }

            $queryinactive->orderBy('l_name');

            $query->where('is_Deleted', 0)->orderBy('l_name');
            $inactiveins = $queryinactive->paginate(10);

            $i_accounts = $query->paginate(10);
            $ranks = tblrank::get()->sortBy('rank');
            $instructoracc = tblinstructor::where('is_Deleted', 0)->get();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.instructor.i-instructor-component',
        [
            'i_accounts' => $i_accounts,
            'ranks' => $ranks,
            'instructoracc' => $instructoracc,
            'inactiveins' => $inactiveins
        ])->layout('layouts.admin.abase');
    }
}
