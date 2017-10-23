<?php

namespace App\Policies;

use App\Expense;
use App\User;


class ExpensePolicy extends AbstractPolicy
{

    public function showCategory($user, $model)
    {
        return $user->isDepLeader();
    }

    public function show(User $user, Expense $expense)
    {
        return $user->isDepLeader();
    }

    public function update($user, $expense)
    {
        return $user->isDepLeader();
    }

    public function delete($user, $expense)
    {
        return $user->isDepLeader();
    }

}
