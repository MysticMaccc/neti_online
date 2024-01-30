<?php

namespace App\Http\Livewire\Admin\Admin;

use App\Models\DialingCode;
use App\Models\tblcompany;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AdminManageUsersComponent extends Component
{
    use WithPagination;
    use ConsoleLog;
    use AuthorizesRequests;
    protected $paginationTheme = 'bootstrap';
    public $search;
    public $email;
    public $dep_type;
    public $lastname;
    public $middlename;
    public $firstname;
    public $password;
    public $contact_num;
    public $u_type;
    protected $passwordhash;
    protected $hashid;
    public $userid;
    public $company_id;
    public $suffix;
    public $u_type_filter;
    public $u_type_value = null;
    public $d_code;
    public $d_code_edit;
    public $edit_country_code;
    public $edit_dialing_code_id;
    public $edit_dialing_code;

    public function mount()
    {
        Gate::authorize('authorizeAdminComponents', 38);
    }

    public function updatedUTypeFilter($u_type_value)
    {
         $this->u_type_value = $u_type_value;
         $this->render();
    }

    public function isactive($id){
        try 
        {
            $user = User::find($id);

            if ($user) {
                $user->update([
                    'is_active' => 1
                ]);
        
                $this->dispatchBrowserEvent('danielsweetalert',[
                    'position' => 'middle',
                    'icon' => 'success',
                    'title' => 'Activated',
                    'confirmbtn' => 'Ok'
                ]);
            }else{
                $this->dispatchBrowserEvent('danielsweetalert',[
                    'position' => 'middle',
                    'icon' => 'warning',
                    'title' => 'Oops! Something went wrong.',
                    'confirmbtn' => 'Ok'
                ]);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function update()
    {
        try 
        {
            $passwordhash = Hash::make($this->password);
            $user = User::find($this->userid);
            $user->l_name = $this->lastname;
            $user->f_name =   $this->firstname;
            $user->m_name = $this->middlename;
            $user->email =  $this->email;
            $user->password_tip = $this->password;
            $user->password =  $passwordhash;
            $user->contact_num =  $this->contact_num;
            $user->u_type =  $this->u_type;
            $user->dep_type =  $this->dep_type;
            $user->company_id = $this->company_id;
            $user->suffix =  $this->suffix;
            $user->dialing_code_id = $this->d_code_edit;
            $user->save();

            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Update successfully created',
                'confirmbtn' => 'Ok'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function assignRoles($user_id)
    {
            
            Session::put('userid' , $user_id);

            return redirect()->route('a.assign-roles');
    }

    public function edit($id)
    {
        try 
        {
            $user = User::find($id);
            $this->userid = $user->id;
            $this->lastname = $user->l_name;
            $this->firstname = $user->f_name;
            $this->middlename = $user->m_name;
            $this->email = $user->email;
            $this->password = $user->password_tip;
            $this->contact_num = $user->contact_num;
            $this->u_type = $user->u_type;
            $this->dep_type = $user->dep_type;
            $this->company_id = $user->company_id;
            $this->suffix = $user->suffix;
            $this->edit_country_code = $user->dialing_code->country_code;
            $this->edit_dialing_code_id = $user->dialing_code->id;
            $this->edit_dialing_code = $user->dialing_code->dialing_code;
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function inactive($id){
        try 
        {
            $user = User::find($id);
            // dd($id);
            if ($user) {
                $user->update([
                    'is_active' => 0
                ]);
        
                $this->dispatchBrowserEvent('danielsweetalert',[
                    'position' => 'middle',
                    'icon' => 'success',
                    'title' => 'Deactivated',
                    'confirmbtn' => 'Ok'
                ]);
            }else{
                $this->dispatchBrowserEvent('danielsweetalert',[
                    'position' => 'middle',
                    'icon' => 'warning',
                    'title' => 'Oops! Something went wrong.',
                    'confirmbtn' => 'Ok'
                ]);
            }
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }


    public function addadmin(){
        try 
        {
            $query = user::orderBy('user_id', 'DESC')->first();
            $userid = $query->user_id;
            $userid++;

            $hashid = hash('sha256', $userid);
            $passwordhash = Hash::make($this->password);

            $this->validate([
                'firstname' => 'required',
                'middlename' => 'nullable',
                'lastname' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:8',
                'email' => 'required|email|unique:users,email',
                'contact_num' => 'required|unique:users,contact_num',
                'u_type' => 'required',
                'd_code' => 'required'
                // 'contact_num' => 'required|regex:/^[0-9]{11}$/|unique:users,contact_num',
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
                'contact_num' => $this->contact_num,
                'is_active' => 1,
                'u_type' => $this->u_type,
                'dep_type' => $this->dep_type,
                'company_id' => $this->company_id,
                'suffix' => $this->suffix,
                'dialing_code_id' => $this->d_code
            ]);

            $this->dispatchBrowserEvent('danielsweetalert',[
                'position' => 'middle',
                'icon' => 'success',
                'title' => 'Administrator successfully created',
                'confirmbtn' => 'Ok'
            ]);

            $this->dispatchBrowserEvent('d_modal',[
                'id' => '#addadminmodal',
                'do' => 'hide'
            ]);
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }
    
    public function generatePassword()
    {
        $length = 12; 
        $uppercase = true;
        $lowercase = true;
        $numbers = true;
        $symbols = true;

        $characters = '';
        $characters .= $uppercase ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : '';
        $characters .= $lowercase ? 'abcdefghijklmnopqrstuvwxyz' : '';
        $characters .= $numbers ? '0123456789' : '';
        $characters .= $symbols ? '@#$%^&*]+$' : '';

        $password = '';
        $characterSetLength = strlen($characters);

        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[random_int(0, $characterSetLength - 1)];
        }

        $this->password = $password;
    }

    public function render()
    {
        try 
        {
            $dialing_code_data = DialingCode::all();
            $all_company = tblcompany::where('deletedid', 0)->orderBy('company', 'ASC')->get();

            $query = User::whereIn('u_type', [0,1,2,3,4,5])->with('company');

            if (!is_null($this->u_type_value)) {
                $query->where('u_type', $this->u_type_value);
            }

            if ($this->search) {
                $searchTerm = '%' . $this->search . '%';
                $query->where(function ($q) use ($searchTerm) {
                            $q->Where('f_name', 'like', $searchTerm)
                                ->orWhere('m_name', 'like', $searchTerm)
                                ->orWhere('l_name', 'like', $searchTerm)
                                ->orWhere('email', 'like', $searchTerm);
                        })
                        ->orWhereHas('company', function ($q) use ($searchTerm) {
                            $q->where('company', 'like', $searchTerm);
                        });
            }
            
            $a_accounts = $query->paginate(10);
            
            $a_accountsall = User::count();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
        
        return view('livewire.admin.admin.admin-manage-users-component',[
            'a_accounts' => $a_accounts,
            'a_accountsall' => $a_accountsall,
            'all_company' => $all_company,
            'dialing_code_data' => $dialing_code_data
        ])->layout('layouts.admin.abase');
    }
}
