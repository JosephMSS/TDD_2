<?php

namespace App\Policies;

use App\Models\Repository;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RepositoryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    /**
     * Un vez creada la politica de acceso debemos registrala en app\Providers\AuthServiceProvider.php
     */
    public function pass(User $user, Repository $repository)
    {
        return $user->id == $repository->user_id;
    }
}
