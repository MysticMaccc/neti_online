<?php

namespace App\Http\Livewire\Admin\Admin;

use App\Models\adminroles;
use App\Models\roles;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Lean\ConsoleLog\ConsoleLog;
use Livewire\Component;
use Livewire\WithPagination;

class AssignRolesComponent extends Component
{
    use ConsoleLog;
    public $selectedRoles = [];
    use WithPagination;
    public $user_id;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        try 
        {
            $this->user_id = Session::get('userid');
            $user = User::where('user_id' , '=' ,Session::get('userid'))->first();

            $selectRoles = roles::paginate(10);
            $startNo = ($selectRoles->currentPage() - 1) * $selectRoles->perPage(10) + 1;
            $t_allschedules = $selectRoles->total();

            $adminroles = adminroles::where('user_id' , '=' , Session::get('userid'))->paginate(8);
            $AdminstartNo = ($adminroles->currentPage() - 1) * $adminroles->perPage(8) + 1;
            $Admint_allschedules = $adminroles->total();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }

        return view('livewire.admin.admin.assign-roles-component',
        [
            'user' => $user , 
            'selectRoles' => $selectRoles , 
            'startNo' => $startNo , 
            't_allschedules' => $t_allschedules , 
            'adminroles' => $adminroles , 
            'AdminstartNo' => $AdminstartNo , 
            'Admint_allschedules' => $Admint_allschedules
        ])->layout('layouts.admin.abase');
    }


    public function addRoles()
    {
        try 
        {
            foreach ($this->selectedRoles as $roleId) {
                // Do something with each selected role ID
                // dump($this->user_id);
                $this->saveRole($roleId);
            }

            // Dispatch success event
            request()->session()->flash('alert-success', 'Roles successfully assigned to user!');

            return redirect()->route('a.assign-roles');
            
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function saveRole($roleid)
    {
        try 
        {
            $add_role = new adminroles();
            $add_role->user_id = $this->user_id;
            $add_role->role_id = $roleid;
            $add_role->save();
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

    public function deleteRole($adminRoleid)
    {
        try 
        {
            $delete_role = adminroles::find($adminRoleid);
            $delete_role->delete();
            return redirect()->route('a.assign-roles');
        } 
        catch (\Exception $e) 
        {
            $this->consoleLog($e->getMessage());
        }
    }

}
