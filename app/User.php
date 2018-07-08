<?php

namespace App;

use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $unreadNotifications
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $role_id
 * @property string $phone
 * @property string $phone_work
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRoleId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePhoneWork($value)
 * @property integer $department_id
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDepartmentId($value)
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $readNotifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Task[] $tasks
 * @property-read \App\Department $department
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Lead[] $leads
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Contact[] $contacts
 * @property string $number
 * @property string $code

 * @property string $issued
 * @property string $address
 * @property string $date
 * @property float $bonus
 * @method static \Illuminate\Database\Query\Builder|\App\User whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereIssued($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereBonus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User filterDepartment($departmentId = null)
 * @property bool $blocked
 * @method static \Illuminate\Database\Query\Builder|\App\User whereBlocked($value)
 * @property int $team_id
 * @method static \Illuminate\Database\Query\Builder|\App\User whereTeamId($value)
 */
class User extends Authenticatable
{
    use Notifiable;
	
	public static $statuses = [
		'active' => 'Активные', 
		'banned' => 'Заблокированные', 
		'all' => 'Все пользователи',
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const MANAGER_ID = 1;
    const LEADER_ID = 2;
    const DEPLEADER_ID = 4;
    const ADMIN_ID = 3;

    public static $roles = [
        self::MANAGER_ID => 'Менеджер', self::LEADER_ID => 'Руководитель отдела', self::ADMIN_ID => 'Директор', self::DEPLEADER_ID => 'Управляющий директор'
    ];
	
	public static $depleader_roles = [
        self::MANAGER_ID => 'Менеджер', self::LEADER_ID => 'Руководитель отдела', self::DEPLEADER_ID => 'Управляющий директор'
    ];
	
	public static $leader_roles = [
        self::MANAGER_ID => 'Менеджер', self::LEADER_ID => 'Руководитель отдела'
    ];


    public function identifiableName()
    {
        return $this->name;
    }
	
	public function getFio()
    {
		return $this->lastname.' '.$this->name.' '.$this->phathername;
    }

    public function getUrlAvatar()
    {
		if($this->filename){
			return '/uploads/images/users/100x100/'.$this->filename;
		} else {
			return '/img/images.png';
		}
    }

    public function getRevisionUnknownString()
    {
        return '[удалено]';
    }

    public function getPost()
    {
        return self::$roles[$this->role_id];
    }

    public function tasks()
    {
		//return $this->belongsToMany('App\Task', 'tasks_users');
        return $this->hasMany(Task::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function isAdmin()
    {
        return $this->role_id == self::ADMIN_ID;
    }

    public function isManager()
    {
        return $this->role_id == self::MANAGER_ID;
    }

    public function isDepLeader()
    {
        return $this->role_id == self::DEPLEADER_ID;
    }

    public function isLeader()
    {
        return $this->role_id == self::LEADER_ID;
    }
	
	public function getProfit($departmentId = null, $dateStart, $dateEnd)
    {
        return Task::whereUserId($this->id)->where('type', '=', 'approved_payment')
                ->where('completed', '=', 'yes')
                ->where('updated_at', '>=', $dateStart)
                ->where('updated_at', '<=', $dateEnd)->sum('cost');
    }

    public function getPlannedProfit($departmentId = null, $dateStart, $dateEnd)
    {
        return Task::whereUserId($this->id)->where('type', '=', 'approved_payment')
                ->where('deadline', '>=', $dateStart)
                ->where('deadline', '<=', $dateEnd)->sum('cost');
    }
	
	public function getProfitManager($departmentId = null, $dateStart, $dateEnd)
    {
        $sum = $this->getProfit($departmentId, $dateStart, $dateEnd);
        return $sum * $this->bonus / 100;
    }

    public function scopeFilterDepartment($query, $departmentId = null)
    {
        if ( $departmentId )
        {
            $userIds = User::whereDepartmentId($departmentId)->pluck('id');
            $query = $query->whereIN('id', $userIds);
        }

        return $query;
    }

    public function scopeFilterTeam($query, $teamId = null)
    {
        if ( $teamId )
        {
            $userIds = User::whereTeamId($teamId)->pluck('id');
            $query = $query->whereIN('id', $userIds);
        }

        return $query;
    }

    public function isBlocked()
    {
        return $this->blocked == 1;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function getDateAttribute($value)
    {
        if ( $value )
        {
            return Carbon::parse($value)->format('Y-m-d');
        }
    }

    public function scopeTypeUser($query, $value = 'active')
    {
        if ( $value == 'banned' )
        {
            $query = $query->whereBlocked(1);
        }
        if ( $value == 'active' )
        {
            $query = $query->whereBlocked(0);
        }

        return $query;
    }
    protected function getDateFormatted($value)
    {
        if ( $value )
        {
            return Carbon::parse($value)->format('Y-m-d H:i');
        }
    }

    public function getCreatedAtAttribute($value)
    {
        return $this->getDateFormatted($value);
    }

    public function getUpdatedAtAttribute($value)
    {
        return $this->getDateFormatted($value);
    }


}
