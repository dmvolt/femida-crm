<?php

namespace App\Policies;

use App\Department;
use App\Task;
use App\Team;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy extends AbstractPolicy
{
    public function showAll(User $user)
    {
        return $user->isAdmin();
    }

    public function show(User $user, Task $task)
    {
        if ( $this->showAll($user) )
        {
            return true;
        }

        if ( $user->isDepLeader() )
        {
            $teamIds = [$task->author->team_id, $task->user->team_id];
            $depIds = Team::whereIn('id', $teamIds)->pluck('department_id')->toArray();

            $isMyDep = in_array($user->department_id, $depIds);

            if ( $isMyDep )
            {
                return true;
            }

        }

        if ( $user->isManager() || $user->isLeader() )
        {
            $isMyTeam = $task->author->team_id == $user->team_id || $task->user->team_id == $user->team_id;

            if ( $isMyTeam )
            {
                return true;
            }
        }

        return ($user->id == $task->user_id) || ($user->id == $task->author_id);
    }


    public function update(User $user, Task $task)
    {
        if ( $this->showAll($user) )
        {
            return true;
        }

        if ( $user->isDepLeader() )
        {
            $teamIds = [$task->author->team_id, $task->user->team_id];
            $depIds = Team::whereIn('id', $teamIds)->pluck('department_id')->toArray();

            $isMyDep = in_array($user->department_id, $depIds);

            if ( $isMyDep )
            {
                return true;
            }

        }

        if ( $user->isLeader() )
        {
            $isMyTeam = $task->author->team_id == $user->team_id || $task->user->team_id == $user->team_id;

            if ( $isMyTeam )
            {
                return true;
            }
        }

        return ($user->id == $task->user_id) || ($user->id == $task->author_id);
    }

    public function delete(User $user, Task $task)
    {
        return $this->update($user, $task);
    }
}
