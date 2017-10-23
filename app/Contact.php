<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Contact
 *
 * @property integer $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property integer $origin_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact wherePhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereOriginId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $user_id
 * @property-read \App\User $user
 * @property-read \App\ContactData $data
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereUserId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ContactActivity[] $activities
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Lead[] $leads
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Task[] $tasks
 * @property-read mixed $created_at_formatted
 * @property-read mixed $updated_at_formatted
 * @method static \Illuminate\Database\Query\Builder|\App\Contact topSearch($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property string $other_phone
 * @property string $series
 * @property string $number
 * @property string $issued
 * @property string $code
 * @property string $issuing_address
 * @property string $date
 * @property string $snils
 * @property string $inn
 * @property string $gender
 * @property string $birthday
 * @property string $address
 * @property string $policy
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereOtherPhone($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereSeries($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereIssued($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereIssuingAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereDate($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereSnils($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereInn($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereBirthday($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact whereAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact wherePolicy($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact filterDepartment($departmentId = null)
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Query\Builder|\App\Contact filterLeads($type = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact filterPayments($type = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact filterService($serviceId = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact filterLeadStatuses($status_id = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact filterLeadUpdate($dateValue = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact filterStatusUpdate($dateValue = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Contact filterTaskStatuses($type = null)
 */
class Contact extends Model
{
    use RevisionableTrait;
    use Notifiable;

    protected $revisionCreationsEnabled = true;
	
    protected $revisionFormattedFieldNames = array(
        'name' => 'ФИО',
        'phone' => 'Телефон',
        'email' => 'E-mail',
        'user_id' => 'Менеджер',
        'origin_id' => 'Источник клиента',
        'data' => 'Паспортные данные',
    );
	
    protected $fillable = [
        'name',
        'phone',
        'email',
        'created_at',
        'user_id',
    ];

    public function identifiableName()
    {
        return $this->name;
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function data()
    {
        return $this->hasOne('App\ContactData');
    }

    public function activities()
    {
        return $this->hasMany('App\ContactActivity')->orderBy('id', 'desc');
    }

    public function folders()
    {
        return $this->hasMany('App\Folder')->with('folders')->whereParentId(0);
    }

    public function leads()
    {
        return $this->hasMany('App\Lead');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task')->orderBy('deadline');
    }
	
	public function lastactivity()
	{
		if ( $this->hasMany('App\ContactActivity')->count() > 0 )
		{
			return $this->hasMany('App\ContactActivity')->orderBy('id', 'desc')->first()->text;
		}
		else
		{
			return '';
		}
	}

    public function whereHistories()
    {
        $contactId = $this->id;
        $taskIds = $this->tasks->pluck('id')->toArray();
        $leadIds = $this->leads->pluck('id')->toArray();

        $histories = new \Venturecraft\Revisionable\Revision();
        $histories = $histories->where(function ($query) use ($contactId) {
            $query = $query->where('revisionable_type', 'App\Contact');
            $query = $query->where('revisionable_id', $contactId);

            return $query;
        });

        $histories = $histories->orWhere(function ($query) use ($taskIds) {
            $query = $query->where('revisionable_type', 'App\Task');
            $query = $query->whereIn('revisionable_id', $taskIds);

            return $query;
        });

        $histories = $histories->orWhere(function ($query) use ($leadIds) {
            $query = $query->where('revisionable_type', 'App\Lead');
            $query = $query->whereIn('revisionable_id', $leadIds);

            return $query;
        });

        $histories = $histories->orderBy('updated_at', 'DESC');

        return $histories;
    }

    public function scopeFilterDepartment($query, $departmentId = null)
    {
        if ( $departmentId )
        {
            $userIds = User::whereDepartmentId($departmentId)->pluck('id');
            $query = $query->whereIN('user_id', $userIds);
        }

        return $query;
    }

    public function scopeFilterTeam($query, $teamId = null)
    {
        if ( $teamId )
        {
            $userIds = User::whereTeamId($teamId)->pluck('id');
            $query = $query->whereIN('user_id', $userIds);
        }

        return $query;
    }


    public function scopeFilterLeads($query, $type = null)
    {
        $closedStatusesIds = LeadStatus::getClosedStatuses()->pluck('id')->toArray();
        if ($type == 'yes')
        {
            $query->whereHas('leads', function ($query) use($closedStatusesIds) {
                $query->whereNotIn('status_id', $closedStatusesIds);
            });
        }
        if ($type == 'no')
        {
            $query->whereHas('leads', function ($query) use($closedStatusesIds) {
                $query->whereIn('status_id', $closedStatusesIds);
            });
        }
        return $query;
    }

    public function scopeFilterLeadStatuses($query, $status_id = null)
    {
        if ( $status_id )
        {
            $query->whereHas('leads', function ($query) use($status_id) {
                $query->where('status_id', '=', $status_id);
            });
        }

        return $query;
    }

    public function scopeFilterTaskStatuses($query, $type = null)
    {
        if ( $type )
        {
            $query->whereHas('tasks', function ($query) use($type) {
                $query->where('type', '=', $type);
            });
        }

        return $query;
    }

    public function scopeFilterLeadUpdate($query, $dateValue = null)
    {
        $dates = explode('|', $dateValue);
        $datesFormatted = [];
        foreach ($dates as $value) {
            $datesFormatted[] = $this->getDateFormatted($value);
        }

        $query->whereHas('leads', function ($query) use($datesFormatted) {
            if ( $datesFormatted[0] )
            {
                $query->where('updated_at', '>=', $datesFormatted[0]);
            }
            if ( $datesFormatted[1] )
            {
                $query->where('updated_at', '<=', $datesFormatted[1]);
            }
        });


        return $query;
    }

    public function scopeFilterStatusUpdate($query, $dateValue = null)
    {
        $dates = explode('|', $dateValue);
        $datesFormatted = [];
        foreach ($dates as $value) {
            $datesFormatted[] = $this->getDateFormatted($value);
        }

        $query->whereHas('tasks', function ($query) use($datesFormatted) {
            if ( $datesFormatted[0] )
            {
                $query->where('updated_at', '>=', $datesFormatted[0]);
            }
            if ( $datesFormatted[1] )
            {
                $query->where('updated_at', '<=', $datesFormatted[1]);
            }
        });

        return $query;
    }

    public function getPhoneAttribute($value)
    {
        return $this->phone($value) ?: $value;
    }

    public function scopeFilterService($query, $serviceId = null)
    {
        if ($serviceId)
        {
            $query->whereHas('leads', function ($query) use($serviceId) {
                $query->where('service_id', '=', $serviceId);
            });
        }
        return $query;
    }

    public function getNameAttribute($value)
    {
        if ($value)
		{
			return $value;
		} 
		else 
		{
			return 'имя не указано';
		}
    }

    public function scopeFilterPayments($query, $type = null)
    {
        if ($type == 'yes')
        {
            $query->whereHas('leads.tasks', function ($query) use($type) {
                $query->where('type', '=', 'approved_payment')->where('completed', '=', 'no');
            });
        }

        if ($type == 'no')
        {
            $query->whereHas('leads.tasks', function ($query) use($type) {
                $query->where('type', '=', 'approved_payment')->where('completed', '=', 'yes');
            });
        }
        return $query;
    }

    public function scopeTopSearch($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            $query->where('email','like','%'.$value.'%');
            $query->orWhere('name','like','%'.$value.'%');

            $phone = preg_replace('|[^0-9\\+]|','',$value);
            if ($phone)
            {
                $query->orWhereRaw(\DB::raw('REPLACE(phone, "-", "") LIKE "%'.$phone.'%"'));
            }
        });
    }
	
	public function scopeAllowedView($query)
    {
        $user = \Auth::user();

        if ( ! $user->isAdmin() )
        {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
	
	public function routeNotificationForSmscRu()
	{
		$to = str_replace(' ', '', $this->phone);
		$to = str_replace('(', '', $to);
		$to = str_replace(')', '', $to);
		return $to;
	}
}
