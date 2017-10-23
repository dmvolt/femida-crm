<?php

namespace App\Policies;

class ServicePolicy extends AbstractPolicy
{

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function showCategory($user, $model)
    {
        return $user->isAdmin() || $user->isDepLeader();
    }

    public function show($user, $model)
    {
        return $user->isAdmin() || $user->isDepLeader();
    }

}
