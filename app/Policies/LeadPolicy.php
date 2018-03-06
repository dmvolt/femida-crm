<?php

namespace App\Policies;

use App\Lead;
use App\Team;
use App\User;

class LeadPolicy extends AbstractPolicy
{

    public function show(User $user, Lead $lead)
    {
		return $lead->department_id  == $user->department_id;
    }

    public function update(User $user, Lead $lead)
    {
        if ( $lead->user_id == 0 )
        {
            return $lead->department_id  == $user->department_id;
        }

        if ( $user->isDepLeader() )
        {
            $isMyDep = $lead->user->department_id == $user->department_id;

            if ( $isMyDep )
            {
                return true;
            }

        }

        return ($user->id == $lead->user_id) ||
            ($user->isLeader() && $lead->user->team_id == $user->team_id);
    }

    public function delete(User $user, Lead $lead)
    {
        //return $this->update($user, $lead);
		return $user->isAdmin();
    }
}
