<?php

namespace App;

use Carbon\Carbon;

/**
 * App\LeadStatus
 *
 * @property integer $id
 * @property string $name
 * @property string $default
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\LeadStatus whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadStatus whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadStatus whereDefault($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Lead[] $leads
 * @property string $type
 * @property string $color
 * @method static \Illuminate\Database\Query\Builder|\App\LeadStatus whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\LeadStatus whereColor($value)
 */
class LeadStatus extends Model
{
    public static $types = [
        'default',
        'normal',
        'closed',
    ];

    public static $typeNames = [
        'default' => 'По умолчанию',
        'normal' => 'Обычный',
        'closed' => 'Закрытый',
    ];

    public static $colors = [
        'success',
        'info',
        'warning',
        'danger',
    ];

    public static $colorNames = [
        'success' => 'Зеленый',
        'info' => 'Синий',
        'warning' => 'Желтый',
        'danger' => 'Красный',
    ];

    public static function getDefaultStatus()
    {
        $firstDefaultStatus = LeadStatus::whereType('default')->first();

        if ( $firstDefaultStatus )
        {
            return $firstDefaultStatus->id;
        }

        return 0;
    }

    public static function getClosedStatuses()
    {
        return LeadStatus::whereType('closed')->get();
    }

    public function leads()
    {
        return $this->hasMany('App\Lead', 'status_id');
    }

    public function identifiableName()
    {
        return $this->name;
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

    public function getPlannedProfit($departmentId = null, $teamId = null, $dateStart, $dateEnd)
    {
        $statusId = $this->id;
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


        return $tasks->whereHas('leads', function ($query) use ($statusId) {
            $query->where('status_id', '=', $statusId);
        })
            ->where('type', '=', 'approved_payment')
            ->where('deadline', '>=', $dateStart)
            ->where('deadline', '<=', $dateEnd)->sum('cost');
    }

    public function getProfit($departmentId = null, $teamId = null, $dateStart, $dateEnd)
    {
        $statusId = $this->id;
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


        return $tasks->whereHas('leads', function ($query) use ($statusId) {
            $query->where('status_id', '=', $statusId);
        })
            ->where('type', '=', 'approved_payment')
            ->where('completed', '=', 'yes')
            ->where('updated_at', '>=', $dateStart)
            ->where('updated_at', '<=', $dateEnd)->sum('cost');
    }

}
