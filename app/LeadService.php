<?php

namespace App;

use Carbon\Carbon;

/**
 * App\LeadService
 *
 * @property integer $id
 * @property string $name
 * @property float $cost
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\LeadService whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadService whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadService whereCost($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadService whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadService whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ServicePayment[] $payments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Lead[] $leads
 */

class LeadService extends Model
{

    public function payments()
    {
        return $this->hasMany(ServicePayment::class, 'service_id');
    }

    public function leads()
    {
        return $this->hasMany('App\Lead', 'service_id');
    }


    public function getLeadsCount($departmentId = null, $teamId = null, Carbon $dateStart, Carbon $dateEnd)
    {
        return $this->leads()->where(function ($query)  use($dateStart, $dateEnd, $departmentId, $teamId) {
            $query->where('created_at', '>=', $dateStart);
            $query->where('created_at', '<=', $dateEnd);

            if ( $departmentId )
            {
                $userIds = User::whereDepartmentId($departmentId)->pluck('id');
                $query->whereIN('user_id', $userIds);
            }

            if ( $teamId )
            {
                $userIds = User::whereTeamId($teamId)->pluck('id');
                $query = $query->whereIN('user_id', $userIds);
            }

            return $query;

        })->count();
    }

    public function getTaskCount($departmentId = null, $teamId = null, Carbon $dateStart, Carbon $dateEnd)
    {
        $serviceId = $this->id;
        $tasks = new Task();

        if ( $departmentId )
        {
            $userIds = User::whereDepartmentId($departmentId)->pluck('id');
            $tasks = $tasks->whereIN('user_id', $userIds);
        }

        if ( $teamId )
        {
            $userIds = User::whereTeamId($teamId)->pluck('id');
            $tasks = $tasks->whereIN('user_id', $userIds);
        }


        return $tasks->whereHas('leads', function ($query) use ($serviceId) {
            $query->where('service_id', '=', $serviceId);
        })
            ->where('type', '=', 'approved_payment')
            ->where('completed', '=', 'yes')
            ->where('updated_at', '>=', $dateStart)
            ->where('updated_at', '<=', $dateEnd)->count();
    }

    public function getPlannedProfit($departmentId = null, $teamId = null, $dateStart, $dateEnd)
    {
        $serviceId = $this->id;
        $tasks = new Task();

        if ( $departmentId )
        {
            $userIds = User::whereDepartmentId($departmentId)->pluck('id');
            $tasks = $tasks->whereIN('user_id', $userIds);
        }

        if ( $teamId )
        {
            $userIds = User::whereTeamId($teamId)->pluck('id');
            $tasks = $tasks->whereIN('user_id', $userIds);
        }

        return $tasks->whereHas('leads', function ($query) use ($serviceId) {
            $query->where('service_id', '=', $serviceId);
        })
            ->where('type', '=', 'approved_payment')
            ->where('deadline', '>=', $dateStart)
            ->where('deadline', '<=', $dateEnd)->sum('cost');
    }

    public function getProfit($departmentId = null, $teamId = null,  $dateStart, $dateEnd)
    {
        $serviceId = $this->id;
        $tasks = new Task();

        if ( $departmentId )
        {
            $userIds = User::whereDepartmentId($departmentId)->pluck('id');
            $tasks = $tasks->whereIN('user_id', $userIds);
        }

        if ( $teamId )
        {
            $userIds = User::whereTeamId($teamId)->pluck('id');
            $tasks = $tasks->whereIN('user_id', $userIds);
        }


        return $tasks->whereHas('leads', function ($query) use ($serviceId) {
            $query->where('service_id', '=', $serviceId);
        })
            ->where('type', '=', 'approved_payment')
            ->where('completed', '=', 'yes')
            ->where('updated_at', '>=', $dateStart)
            ->where('updated_at', '<=', $dateEnd)->sum('cost');
    }

}
