<?php

namespace App;

use Carbon\Carbon;
use Venturecraft\Revisionable\RevisionableTrait;

/**
 * App\Lead
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property integer $user_id
 * @property integer $contact_id
 * @property float $budget
 * @property integer $status_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereContactId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereBudget($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereStatusId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property integer $service_id
 * @property-read \App\User $user
 * @property-read \App\LeadService $service
 * @property-read \App\LeadStatus $status
 * @property-read \App\Contact $contact
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereServiceId($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Task[] $tasks
 * @property-read mixed $created_at_formatted
 * @property-read mixed $updated_at_formatted
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LeadActivity[] $activities
 * @method static \Illuminate\Database\Query\Builder|\App\Lead allowed()
 * @property-read mixed $status_html
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @method static \Illuminate\Database\Query\Builder|\App\Lead topSearch($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead allowedView()
 * @method static \Illuminate\Database\Query\Builder|\App\Lead searchStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead closedStatuses()
 * @method static \Illuminate\Database\Query\Builder|\App\Lead filterDepartment($departmentId = null)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead withStatusOpened()
 * @property integer $department_id
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereDepartmentId($value)
 * @property string $number
 * @property string $city
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Lead whereNumber($value)
 */
class Lead extends Model
{
    use RevisionableTrait;

    protected $revisionCreationsEnabled = true;
    protected $revisionFormattedFieldNames = array(
        'name' => 'Название',
        'status_id' => 'Статус',
        'description' => 'Описание',
        'contact_id' => 'Контакт',
        'budget' => 'Бюджет',
        'user_id' => 'Менеджер',
        'created_at' => 'Создано',
        'updated_at' => 'Обновлено',
    );

    public function identifiableName()
    {
        return $this->name;
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function service()
    {
        return $this->belongsTo('App\LeadService', 'service_id');
    }

    public function status()
    {
        return $this->belongsTo('App\LeadStatus', 'status_id');
    }
	
	public function department()
    {
        return $this->belongsTo('App\Department', 'department_id');
    }

    public function getStatusHtmlAttribute($value = null)
    {
        return '<a href="'.route('leads.view', ['leadId' => $this->id]).'" class="pull-right btn btn-xs btn-default">'.$this->status->name.'</a>';
    }


    public function activities()
    {
        return $this->hasMany('App\LeadActivity')->orderBy('id', 'desc');
    }

    public function scopeSearchStatus($query, $value)
    {
        switch ($value)
        {
            case 'open':
            {
                return $query->withStatusOpened();
            }
            case 'all':{
                return $query;
            }
            default:
            {
                return $query->where('status_id', '=', $value);
            }
        }
    }

    public function scopeClosedStatuses($query)
    {
        $closedStatusesIds = LeadStatus::whereType('closed')->pluck('id')->toArray();
        return $query->whereIn('status_id', $closedStatusesIds);
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


    public function scopeWithStatusOpened($query)
    {
        $openStatusIds = LeadStatus::where('type', '!=', 'closed')->get()->pluck('id');
        return $query->whereIn('status_id', $openStatusIds);
    }

    public function scopeTopSearch($query, $value)
    {
        return $query->where(function ($query) use ($value) {
            return $query->where('name','like','%'.$value.'%')
                ->orWhere('budget','like','%'.$value.'%')
                ->orWhereHas('user', function ($q) use ($value) {
                    $q->whereRaw("name like ?", array("%".$value."%"));
                })
                ->orWhereHas('contact', function ($q) use ($value) {
                    $q->whereRaw("name like ?", array("%".$value."%"));
                })
                ->orWhereHas('status', function ($q) use ($value) {
                    $q->whereRaw("name like ?", array("%".$value."%"));
                });
        });
    }


    public function scopeAllowedView($query)
    {
        $user = \Auth::user();

        if ( ! $user->isAdmin() )
        {
            $query->whereDepartmentId($user->department_id);
        }

        return $query;
    }

    public function createTaskForService()
    {
        $leadTasks = [];

        $deadline = Carbon::now();
        foreach ($this->service->payments as $payment)
        {
            $task = new Task();
            $task->name = 'Подтвердить оплату по сделки №'.$this->id;
            $task->type = 'approved_payment';

            $deadline = $deadline->addDays($payment->days);

            $task->deadline = $deadline->toDateTimeString();
            $task->lead_id = $this->id;
            $task->user_id = $this->user_id;
            $task->contact_id = $this->contact_id;
            $task->author_id = $this->user_id;
            $task->cost = $payment->cost;

            $leadTasks[] = $task;
        }

        $this->tasks()->saveMany($leadTasks);
    }

    public function getPaymentSum()
    {
        return $this->tasks()->whereType('approved_payment')->whereCompleted('yes')->sum('cost');
    }

    public function contact()
    {
        return $this->belongsTo('App\Contact', 'contact_id');
    }

    public function getNameAttribute($value = null)
    {
		
        if ( ! $value )
        {
            return 'Сделка №'.$this->id;
        }

        return $value;
    }
}

