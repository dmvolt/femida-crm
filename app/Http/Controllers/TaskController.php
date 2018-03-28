<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Http\Traits\DefaultControllerTrait;
use App\Lead;
use App\Department;
use App\Notifications\MessageNotification;
use App\Task;
use App\TaskActivity;
use App\TaskNotification;
use App\User;
use App\Notice;

use Auth;
use Cache;
use Carbon\Carbon;
use DataGrid;

use App\Http\Requests;
use Input;

class TaskController extends Controller
{
    use DefaultControllerTrait;
    public $currentMenuId = 'tasks';

    public function index()
    {
		return $this->showType(0);
	}
	
	public function showType($id)
	{
		$newRequest = Task::with(['user', 'contact', 'lead.contact'])->has('contact')->where('type', '=', 'request');
		if (! \Auth::user()->isAdmin() ) 
		{
			$newRequest = $newRequest->where('department_id', '=', \Auth::user()->department_id);
		}

        $tableRequest = DataGrid::source($newRequest);
        $tableRequest->attributes(["class"=>"table table-hover issue-tracker add-task-table"]);

        $tableRequest->add('contact.name','Клиент');
        $tableRequest->add('created_at', 'Дата', true);
        $tableRequest->add('request', 'Действия');

        $tableRequest->row(function ($row) {
            $row->cell('request')->value($row->data->request);
        });

        $tableRequest->orderBy('id','desc');
        $tableRequest->paginate(25);
		
		if (! \Auth::user()->isAdmin() ) 
		{
			$tasks = Task::with(['user', 'contact', 'lead.contact'])->where('type', '!=', 'request')->allowed()->FilterDepartment(\Auth::user()->department_id);
		}
		else
		{
			$tasks = Task::with(['user', 'contact', 'lead.contact'])->where('type', '!=', 'request')->allowed();
		}

		if ( Input::get('search') != '1' )
			$tasks = $tasks->where('deadline', '>=', Carbon::today());
		$tasks->orderBy('deadline');
		
		if ($id != 0 && ( Input::get('search') != '1' ) ) {
			$tasks = $tasks->where('deadline', '<', Carbon::tomorrow());
		}
		
		$fasttypes = '';
		if (true) {
			$typeOptions = ['0' => 'Все типы', '1' => 'Встреча', '2' => 'Звонок', '3' => 'Подтвердить оплату'];
			$typeParams = ['0' => '', '1' => 'appointment', '2' => 'recall', '3' => 'approved_payment'];
			foreach ($typeOptions as $key => $value) {
				$style = ($key == $id) ? 'primary' : 'default';
				$fasttypes .= '<a href="/tasks/types/'.$key.'" class="btn btn-'.$style.'" type="submit">'.$value.'</a></n>';
				/*
				if ($key == 0) 
					$fasttypes .= '<a href="/tasks" class="btn btn-'.$style.'">'.$value.'</a>';
				else
					$fasttypes .= '<a href="/tasks?topSearch=&deadline%5Bfrom%5D='.date('Y-m-d').'&type='.$typeParams[$key].'" class="btn btn-'.$style.'">'.$value.'</a>';
				*/
			}
		}
		
		$bdtypes = ['1' => 'appointment', '2' => 'recall', '3' => 'approved_payment'];
		
		if ($id != 0) {
			$tasks->where('type', '=', $bdtypes[$id]);
		}
		
		/*
		if ( \Auth::user()->isManager() )
		{
			$tasks = $tasks->where('user_id', '=', \Auth::user()->id);
		}
		*/
        if ( Input::get('completed', null) == null )
        {
            $tasks = $tasks->whereCompleted('no');
        }

        $filter = \DataFilter::source($tasks);
        $filter->add('topSearch','Поиск', 'text')->scope('topSearch');
        $filter->add('deadline','Дата выполнения', '\App\Rapyd\Fields\MyDateRange');

        if ( ! \Auth::user()->isManager() )
        {
            $userOptions = [null => 'Все менеджеры'] + User::all()->pluck('name', 'id')->toArray();
            $filter->add('user_id','Менеджер', 'select')->options($userOptions)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Менеджер']);
        }

        $types = [null => 'Все типы'];
        $types += Task::$types;

        $filter->add('type','Тип задачи', 'select')->options($types)->attributes(['data-placeholder' => 'Типы сделки']);
        $filter->add('completed','Статус', 'select')->options(['no' => 'Открытые', 'yes' => 'Закрытые', 'all' => 'Все'])->attributes(['data-placeholder' => 'Статус'])->scope('searchCompleted');

        $contactOptions = [null => 'Все контакты'] + Contact::all()->pluck('name', 'id')->toArray();
        $filter->add('contact_id','Контакт', 'select')->options($contactOptions)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Контакт']);

        $leads = [null => 'Все сделки'] + Lead::AllowedView()->get()->pluck('name', 'id')->toArray();
        $filter->add('lead_id','Сделка', 'select')->options($leads)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Сделки']);

		if ( \Auth::user()->isAdmin() )
		{
			$departments = [null => 'Все филиалы'] + Department::all()->pluck('name', 'id')->toArray();
			$filter->add('department_id', 'Филиал', 'select')->options($departments)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Филиалы']);
		}

        $filter->submit('Фильтровать');
        $filter->reset('Сбросить');
        $filter->build();

        $tasks = DataGrid::source($filter);
        $tasks->attributes(["class"=>"table table-hover issue-tracker"]);

        $tasks->add('status','#');
        $tasks->add('clientName','Клиент');
		$tasks->add('lastcomment','Описание');
        $tasks->add('{{$user->name}}', 'Ответственный');
        $tasks->add('deadline', 'Дата выполнения', true);
        $tasks->add('typeName', 'Тип');

        if ( Auth::user()->isAdmin() )
        {
            $tasks->edit(route('tasks.store'), 'Удалить','delete')->style("width:15px");
        }

        $tasks->orderBy('id','desc');
        $tasks->paginate(25);

        $tasks->row(function ($row) {
            $name = $row->cell('clientName')->value;
            $row->cell('clientName')->value('<a class="client-link" href="#" data-id="'.$row->data->contact_id.'" >'.$name.'<a/>');
            //$row->cell('clientName')->attributes(['class' => 'issue-info']);
			if($row->data->contact) {
				$row->cell('lastcomment')->value = mb_substr($row->data->contact->lastactivity(), 0, 20).((strlen($row->data->contact->lastactivity()) > 20) ? "..." : "");
				$row->cell('lastcomment')->attributes(["title" => $row->data->contact->lastactivity()]);
			}
            $row->cell('status')->value($row->data->status);
        });

        return view('task', compact('tasks', 'filter', 'tableRequest', 'fasttypes'));
	}

    public function show($id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('show', $task);

        return view('tasks.view', compact('task'));
    }

    public function remove($id)
    {
        $task = Task::findOrFail($id);
        $this->authorize('delete', $task);

        $task->delete();
        return json_encode(['status' => 'ok']);
    }

    public function store($id = null)
    {
        if ( $redirect = $this->deleteAction(new Task(), 'tasks') )
        {
            return $redirect;
        }

        $source = $this->getSource($id, new Task());

        $store = \DataForm::source($source);
        $store->add('name','Имя', 'text')->rule('required|min:2');
        $store->add('description','Описание', 'redactor');
        $store->add('deadline','Дата выполнения', 'datetime')->rule('required');
        $store->add('user_id','Ответственный', 'select')->options(User::all()->pluck('name', 'id'))->attributes(['class' => 'chosen-select']);

        $store->add('type','Тип задачи', 'select')->options(Task::$types)->attributes(['class' => 'chosen-select']);
        $store->add('cost','Сумма оплаты', 'number');

        $contacts = [0 => 'Не выбрано'] + Contact::all()->pluck('name', 'id')->toArray();
        $store->add('contact_id','Контакт', 'select')->options($contacts)->attributes(['class' => 'chosen-select']);

        $leads = [0 => 'Не выбрано'] + Lead::allowedView()->get()->pluck('name', 'id')->toArray();
        $store->add('lead_id','Сделка', 'select')->options($leads)->attributes(['class' => 'chosen-select']);

        $store->submit('Сохранить');

        if ( ! $store->model->exists )
        {
            $store->model->author_id = \Auth::user()->id;
        }

        $this->preferValues($store);
        $store->saved(function () use ($store) {
            $task = $store->model;

            if ( $store->action == 'insert' && $task->type == 'appointment' )
            {
                $contact = $task->contact;
                if ( $contact && $contact->phone )
                {
					if($noticeModel = Notice::find(1)){
						
						$text = str_replace(Notice::$variables, array($task->user->department->address, $task->deadline, $task->user->name, $task->user->phone), $noticeModel->text);
						
					} else {
						$text = 'Вам назначена встреча по адресу '.$task->user->department->address.' на '.$task->deadline.' Менеджер '.$task->user->name.', тел. '.$task->user->phone;
					}
					
                    $notifyData = [
                        'type' => 'sms',
                        'text' => $text,
                    ];

                    $contact->notify(new MessageNotification($notifyData));
                }
            }

            return redirect(route('tasks.view', ['taskId' => $task->id]));
        });

        $store->build();

        $title = 'Новая задача';
        return $store->view('tasks.store', compact('store', 'title'));
    }

    public function completed($id)
    {
        $task = Task::findOrFail($id);
        $task->completed = 'yes';
        $task->save();

        return redirect(route('tasks.view', ['taskId' => $task->id]));
    }

    public function getNewRequest($id)
    {
        $task = Task::findOrFail($id);

        if ( $task->type != 'request' )
        {
            return redirect()->back()->with('request_failed', 'Эту заявку взял в обработку менеджер '.$task->user->name);
        }

        // @todo: fix - none contact
        if ( $task->contact )
        {
            if ( ! $task->contact->user_id )
            {
                $task->contact->user_id = \Auth::user()->id;
                $task->contact->save();
            }
        }

        $task->type = 'make_appointment';
        $task->user_id = \Auth::user()->id;
        $task->author_id = \Auth::user()->id;
        $task->deadline = Carbon::tomorrow();
        $task->save();

        Task::updateNewRequestCount();

        return redirect(route('tasks', ['#contact'.$task->contact_id]))->with('param', 'noclose');

    }

    public function addComment($id)
    {
        $task = Task::findOrFail($id);

        $activity = new TaskActivity();
        $activity->type = TaskActivity::TYPES['comment'];
        $activity->text = Input::get('comment');
        $activity->user_id = \Auth::user()->id;

        $task->activities()->save($activity);

        return redirect(route('tasks.view', ['taskId' => $task->id]));
    }

    public function addNotification($id)
    {
        $task = Task::findOrFail($id);

        $notification = new TaskNotification();
        $notification->text = Input::get('text');
        $notification->datetime = Carbon::parse(Input::get('datetime'));

        $task->notifications()->save($notification);

        return redirect(route('tasks.view', ['taskId' => $task->id]));
    }

    public function activity()
    {
        $offset = Input::get('offset', 5);
        $taskId = Input::get('task_id', null);

        $activities = TaskActivity::whereTaskId($taskId)->offset($offset)->limit(5)->get();

        $response = '';
        foreach ($activities as $activity)
        {
            $response .= view('tasks.view.activity.item', ['activity' => $activity])->render();
        }

        return $response;
    }

}
