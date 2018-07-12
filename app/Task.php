<?php

namespace App;

use Cache;
use Carbon\Carbon;
use Gate;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Task
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $deadline
 * @property string $type
 * @property integer $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\User $user
 * @property-read mixed $status
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereDeadline($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $contact_id
 * @property integer $lead_id
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereContactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereLeadId($value)
 * @property string $completed
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereCompleted($value)
 * @property-read mixed $deadline_formatted
 * @property-read mixed $created_at_formatted
 * @property-read mixed $updated_at_formatted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TaskActivity[] $activities
 * @property integer $author_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\TaskNotification[] $notifications
 * @property-read \App\Contact $contact
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereAuthorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task allowed()
 * @method static \Illuminate\Database\Query\Builder|\App\Task topSearch($value)
 * @property-read \App\Lead $lead
 * @property float $cost
 * @method static \Illuminate\Database\Query\Builder|\App\Task whereCost($value)
 * @property-read \App\Lead $leads
 * @property-read mixed $type_name
 * @method static \Illuminate\Database\Query\Builder|\App\Task filterDepartment($departmentId = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Task searchCompleted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Task withPayments()
 * @property-read mixed $client_name
 * @property-read mixed $last_comment
 * @property-read mixed $request
 */
class Task extends Model
{
    use RevisionableTrait;

    protected $appends = ['status'];

    protected $revisionCreationsEnabled = true;
    protected $revisionFormattedFieldNames = array(
        'name' => 'Название',
        'description' => 'Описание',
        'deadline' => 'Дата выполнения',
        'user_id' => 'Назначена',
        'author_id' => 'Автор',
        'contact_id' => 'Контакт',
        'lead_id' => 'Сделка',
        'type' => 'Тип',
        'completed' => 'Выполнено',
    );

    public function identifiableName()
    {
        return $this->name;
    }

    public static $types = [
        'recall' => 'Звонок',
        'make_appointment' => 'Назначить встречу',
        'approved_payment' => 'Подтвердить оплату',
        'appointment'=> 'Встреча',
        'court_hearing' =>'Судебное заседание',
        'request' =>'Новая заявка',
        'cancel' =>'Брак'
    ];

    public static $activeTypes = [
        'appointment' => 'Встреча',
        'recall' => 'Звонок',
        'court_hearing' => 'Заседание',
        'cancel' => 'Брак',
    ];

	// Выборка одного user к данной task
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
	
	// Выборка множества user к данной task
	/* public function users()
    {
		return $this->belongsToMany('App\User', 'tasks_users');
    } */

    public function notifications()
    {
        return $this->hasMany('App\TaskNotification')->orderBy('id', 'desc');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact', 'contact_id');
    }

    public function lead()
    {
        return $this->belongsTo('App\Lead', 'lead_id');
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }

    public function activities()
    {
        return $this->hasMany('App\TaskActivity')->orderBy('id', 'desc');
    }

    public function leads()
    {
        return $this->belongsTo('App\Lead', 'lead_id');
    }
	
	public function income()
    {
        return $this->belongsTo('App\Income', 'income_id');
    }

    public function getTypeNameAttribute()
    {
        return self::$types[$this->type] ?: null;
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

    public function getClientNameAttribute()
    {
        if ( isset($this->lead->contact->name) )
        {
            return $this->lead->contact->name;
        }

        if ( isset($this->contact->name) )
        {
            return $this->contact->name;
        }

        return null;
    }

    public function getLastCommentAttribute()
    {
        $comment = $this->activities->first();
        if ($comment)
        {
            return $comment->text;
        }
    }

    public function getStatusAttribute()
    {
        $statuses = [
            'active' => '<span class="label label-success"><i class="fa fa-calendar" aria-hidden="true"></i> Активная</span>',
            'overdue' => '<span class="label label-danger"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Просрочено</span>',
            'completed' => '<span class="label label-primary"> <i class="fa fa-check" aria-hidden="true"></i> Выполнена</span>',
        ];

        $deadline = strtotime($this->deadline) ;
        if ( $this->isCompleted() )
        {
            return $statuses['completed'];
        }
        elseif( $deadline > 0 && time() >= $deadline )
        {
            return $statuses['overdue'];
        }
        else
        {
            return $statuses['active'];
        }
    }

    public function getRequestAttribute()
    {
        return '<a class="btn confirm btn-primary btn-sm" href="'.route('tasks.getNewRequest', ['taskId' => $this->id]).'">Взять в обработку</a>';
    }

    public static function getNewRequestCount()
    {
        $count = Cache::get('countNewRequest', null);

        if ( $count === null )
        {
            $count = self::updateNewRequestCount();
        }

        return $count;
    }

    public static function updateNewRequestCount()
    {
		$tasks = Task::where('type', '=', 'request')->has('contact');
		if ( \Auth::user() && ! \Auth::user()->isAdmin() ) 
		{
			$tasks = $tasks->where('department_id', '=', \Auth::user()->department_id);
		}
        $count = $tasks->count();
        Cache::put('countNewRequest', $count, 60);

        return $count;
    }

    public function scopeAllowed($query)
    {
        if ( Gate::denies('showAll', new Task()))
        {
            if ( \Auth::user()->isDepLeader() )
            {
                $userIds = User::whereDepartmentId(\Auth::user()->department_id)->pluck('id')->toArray();
                $query = $query->whereIn('user_id', $userIds)->orWhereIN('author_id', $userIds);
            }
            else
            {
                $userIds = User::whereTeamId(\Auth::user()->team_id)->pluck('id')->toArray();
                $query = $query->whereIn('user_id', $userIds)->orWhereIN('author_id', $userIds);
            }
        }

        return $query;
    }

    public function scopeSearchCompleted($query, $value)
    {
        switch ($value)
        {
            case 'all':
            {
                return $query;
            }
            default:
            {
                return $query->where('completed', '=', $value);
            }
        }
    }

    public function scopeWithPayments($query)
    {
        return $query->whereType('approved_payment');
    }

    public function scopeTopSearch($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            return $query->where('name','like','%'.$value.'%')
                ->orWhere('description','like','%'.$value.'%')
                ->orWhereHas('user', function ($q) use ($value) {
                    $q->whereRaw("name like ?", array("%".$value."%"));
                })
                ->orWhereHas('contact', function ($q) use ($value) {
                    $q->whereRaw("name like ?", array("%".$value."%"));
                })
                ->orWhereHas('author', function ($q) use ($value) {
                    $q->whereRaw("name like ?", array("%".$value."%"));
                });
        });
    }

    public function getDeadlineAttribute($value)
    {
        if ( $value )
        {
            return Carbon::parse($value)->format('d.m.Y H:i');
        }
    }
	
	public function cpltd()
    {
        return $this->completed == 'yes';
    }

    public function isCompleted()
    {
        return $this->completed == 'yes';
    }
}
