<?php

namespace App\Policies;

use App\User;
use App\MeetingLogs;
use Illuminate\Auth\Access\HandlesAuthorization;

class MeetingLogsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can see the roles.
     *
     * @param  \App\User  $user
     * @return boolean
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isManager() || $user->isCreator();
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  \App\User  $user
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isManager()|| $user->isCreator();
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  \App\User  $user
     * @param  \App\Role  $role
     * @return boolean
     */
    public function update(User $user)
    {
        return $user->isAdmin() || $user->isManager() || $user->isCreator();
    }	
}
