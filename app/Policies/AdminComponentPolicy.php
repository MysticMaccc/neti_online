<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class AdminComponentPolicy
{
    /**
     * Create a new policy instance.
     */
    public function authorizeComponent(User $user, $role_id)
    {
        return $user->adminroles->pluck('role_id')->contains($role_id)
                ? Response::allow() 
                : Response::deny('You are unauthorized to access this module.');
    }

    public function restrictBillingModule(User $user)
    {
        return $user->email == "noc@neti.com.ph" ? 
        Response::allow() 
        : Response::deny('Coming Soon!');
    }
}
