<?php

namespace App\Policies;

use App\Lead;
use App\Team;
use App\User;

class DepartmentPolicy extends AbstractPolicy
{

    public function showCategory($user, $model)
    {
        return $user->isDepLeader() || $user->isLeader();
    }
}
