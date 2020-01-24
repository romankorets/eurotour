<?php

namespace App\Policies;

use App\Tour;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TourPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any tours.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the tour.
     *
     * @param  \App\User  $user
     * @param  \App\Tour  $tour
     * @return mixed
     */
    public function view(User $user, Tour $tour)
    {
        return true;
    }

    /**
     * Determine whether the user can create tours.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasAccess(['create-tour']);
    }

    /**
     * Determine whether the user can update the tour.
     *
     * @param  \App\User  $user
     * @param  \App\Tour  $tour
     * @return mixed
     */
    public function update(User $user, Tour $tour)
    {
        return $user->hasAccess(['edit-tour']);
    }

    /**
     * Determine whether the user can delete the tour.
     *
     * @param  \App\User  $user
     * @param  \App\Tour  $tour
     * @return mixed
     */
    public function delete(User $user, Tour $tour)
    {
        return $user->hasAccess(['delete-tour']);
    }

    /**
     * Determine whether the user can restore the tour.
     *
     * @param  \App\User  $user
     * @param  \App\Tour  $tour
     * @return mixed
     */
    public function restore(User $user, Tour $tour)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the tour.
     *
     * @param  \App\User  $user
     * @param  \App\Tour  $tour
     * @return mixed
     */
    public function forceDelete(User $user, Tour $tour)
    {
        //
    }
}
