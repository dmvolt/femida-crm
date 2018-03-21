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

	public function showLeader($user)
    {
        if(($user->isLeader() && $user->team_id == 2) || $user->isDepLeader() || ($user->isManager() && $user->team_id == 2)){
			return true;
		}
    }
	
	public function showDepLeader($user)
    {
        return $user->isDepLeader();
    }
	
	public function showManager($user)
    {
        if(($user->isLeader() && $user->team_id == 4) || $user->isDepLeader() || ($user->isManager() && $user->team_id == 4)){
			return true;
		}
    }
	
	public function showYurist($user)
    {
		if(($user->isLeader() && $user->team_id == 3) || $user->isDepLeader() || ($user->isManager() && $user->team_id == 3)){
			return true;
		}
    }
}