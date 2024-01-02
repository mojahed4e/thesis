<?php

namespace App\Policies;

use App\User;
use App\Term;
use Illuminate\Auth\Access\HandlesAuthorization;

class TermPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can see the terms.
     *
     * @param  \App\User  $user
     * @return boolean
     */
    public function viewAny(User $user)
    {
        return $user->isAdmin() || $user->isManager() || $user->isCreator();
    }

    /**
     * Determine whether the user can create terms.
     *
     * @param  \App\User  $user
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can update the term.
     *
     * @param  \App\User  $user
     * @param  \App\Term  $term
     * @return boolean
     */
    public function update(User $user, Term $term)
    {
        return $user->isAdmin() || $user->isManager();
    }
	
	/**
     * Determine whether the user can delete the item.
     *
     * @param  \App\User  $user
     * @param  \App\Term  $term
     * @return boolean
     */
    public function delete(User $user, Term $term)
    {
        return $user->isAdmin();
    }
}