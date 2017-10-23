<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy extends AbstractPolicy
{
    public function showCategory($user, $model)
    {
        return $user->isAdmin() or $user->isDepLeader();
    }

}
