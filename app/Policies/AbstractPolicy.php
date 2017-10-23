<?php

namespace app\Policies;


use Illuminate\Auth\Access\HandlesAuthorization;

abstract class AbstractPolicy
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

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function showCategory($user, $model)
    {
        return true;
    }

}