<?php

namespace App\Http\Controllers;

use App\Contact;
use App\ContactActivity;
use App\ContactOrigin;
use App\Http\Traits\DefaultControllerTrait;
use App\Department;
use App\Lead;
use App\LeadService;
use App\LeadStatus;
use App\Notifications\MessageNotification;
use App\Task;
use App\User;
use Carbon\Carbon;
use Faker\Provider\at_AT\Payment;
use function foo\func;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Mockery\CountValidator\Exception;
use Session;
use Zofe\Rapyd\DataGrid\DataGrid;


class ContactController extends Controller
{
    use DefaultControllerTrait;
    public $currentMenuId = 'contacts';

	public function showDepartment($id)
	{
		$contacts = Contact::with('user');
		
		if ($id != 0) {
			$contacts = $contacts->filterDepartment($id);
		}
		
		$deps = '';
		if (\Auth::user()->isAdmin()) {
			$departmentOptions = ['0' => 'Все филиалы'] + Department::all()->pluck('name', 'id')->toArray();
			foreach ($departmentOptions as $key => $value) {
				$style = ($key == $id) ? 'primary' : 'default';
				$deps .= '<a href="/contacts/departments/'.$key.'" class="btn btn-'.$style.'" type="submit">'.$value.'</a></n>';
			}
		}

		if (! \Auth::user()->isAdmin()) {
			$contacts = $contacts->FilterDepartment(\Auth::user()->department_id);
		}
		
		/*
		if (\Auth::user()->isManager())
		{
			$userIds = Auth::user()->id;
			$contacts = $contacts->where('user_id', '=', $userIds);
		}
		if (\Auth::user()->isDepLeader())
		{
			$contacts = $contacts->FilterDepartment(\Auth::user()->department_id);
		}
		if (\Auth::user()->isLeader())
		{
			$contacts = $contacts->FilterTeam(\Auth::user()->team_id);
		}
		*/
		
		$filter = \DataFilter::source($contacts);
        $filter->add('topSearch','Поиск', 'text')->scope('topSearch');

        $filter->build();

        $grid = DataGrid::source($filter);
		
        $grid->attributes(["class"=>"table table-striped table-hover"]);

        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','ФИО');
        $grid->add('<i class="fa fa-envelope"> </i> {{$email}}','E-mail');
        $grid->add('phone','Телефон');

		$grid->edit(route('contacts.store'), 'Редактировать','modify|delete')->style("width:15px");
        $grid->link(route('contacts.store'), "Новый контакт", "TR");

        $grid->orderBy('id','desc');
        $grid->paginate(50);

        $grid->row(function ($row) {
            $url = route('contacts.restShow');
            $name = $row->cell('name')->value;
            $phone = $row->cell('phone')->value;

            $row->cell('name')->value('<a class="client-link" href="'.$url.'" data-id="'.$row->data->id.'" >'.$name.'<a/>');
            $row->cell('phone')->value('<i class="fa fa-phone"> </i> '.$phone);
        });


        $title = 'Контакты';
        $content = view('contacts', compact('grid', 'title', 'filter', 'deps'));

        return view('default.base', compact('content'));
	}
	
	
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		return $this->showDepartment(0);
    }


    /**
     * @param integer $id
     * @return \Zofe\Rapyd\DataForm\Redirect|\Zofe\Rapyd\DataForm\View
     */
    public function store($id = null)
    {
        if ( $redirect = $this->deleteAction(new Contact(), 'contacts') )
        {
            return $redirect;
        }

        $source = $this->getSource($id, new Contact());
		
        $store = \DataForm::source($source);
        $store->add('name','Имя', 'text')->rule('required|min:2');
		$store->add('phone','Телефон', 'text')->updateValue(substr($source->phone, 2))->rule('required|min:5');
		
        $store->add('email','E-mail', 'text');
        $store->add('origin_id','Источник клиента', 'select')->options(ContactOrigin::all()->pluck('name', 'id'));

        $user = $store->add('user_id','Менеджер', 'select')->options(User::all()->where('department_id', '=', \Auth::user()->department_id)->pluck('name', 'id'))->attributes(['class' => 'chosen-select']);

        if ( ! $source->exists )
        {
            $user->value = \Auth::user()->id;
            Session::remove('contactRedirect');
        }

        $store->add('data.number','Серия и номер', 'text');
        $store->add('data.code','Код', 'text');
        $store->add('data.issued','Выдан', 'text');
        $store->add('data.address','Адрес', 'text');
        $store->add('data.date','Дата', 'date');
		
		$store->add('data.credit_sum', 'Сумма кредита', 'text');
		$store->add('data.credit_target', 'Цель кредита', 'text');
		$store->checkbox('data.is_pledge', 'Залог');
		$store->checkbox('data.is_guarantor', 'Поручитель');
		$store->checkbox('data.is_reference', 'Справка о доходах');
		$store->checkbox('data.is_delay', 'Открытые просрочки');
		$store->add('data.contact_birth', 'Дата рождения', 'text');
		$store->add('data.contact_address', 'Место жительства(факт.)', 'text');
		$store->add('data.contact_inn', 'ИНН', 'text');

        $this->preferValues($store);
        $store->submit('Сохранить');
        $store->saved(function () use ($store, $source) {
            return redirect(Session::get('contactRedirect', '/contacts#contact'.$store->model->id));
        });
        $store->build();

        $title = ! $source->exists ? 'Новый контакт' : 'Редактирование контакта';
        return $store->view('contacts.store', compact('store', 'title'));
    }

    public function restShow(Request $request)
    {
        Session::set('contactRedirect', Input::get('ref'));

        $contact = Contact::with(['user', 'data', 'activities.user', 'leads', 'tasks'])->findOrFail(Input::get('id'));
		
		if($contact->user){
			$depId = $contact->user->department_id;
		} else {
			$depId = $contact->tasks[0]->department_id;
		}
		
		$department = Department::findOrFail($depId);
		
		$user_id = \Auth::user()->id;
		
		$users_result = User::with('team')
				->where('blocked', 0)
				->where('department_id', $depId)
				->orderBy('name')
				->get();
				
		$users = [];
				
		if($users_result){
			foreach($users_result as $value){
				if($value->team_id){
					$users[$value->team->name][] = $value;
				}
			}
		}
		
		$important = [];
		
		if($contact->tasks){
			foreach($contact->tasks as $task){
				if($contact->user->id != $task->user->id){
					$important[$task->user->id]	= $task;
				}
			}
		}		
		
        return view('contacts.show', compact('contact', 'title', 'department', 'users', 'user_id', 'important'));
    }

    public function task($id)
    {
        $contact = Contact::with(['user', 'data', 'activities.user', 'leads', 'tasks'])->findOrFail($id);
		$department = Department::findOrFail($contact->user->department_id);

        $type = Input::get('type', 'recall');
        $deadline = Input::get('deadline');
		
		if (\Auth::user()->isAdmin()) {
			$user_id = Input::get('user_id', \Auth::user()->id);
		}
		else
		{
			$user_id = \Auth::user()->id;
		}

        $task = new Task(); // @todo: check permission
        $task->name = Task::$types[$type] ?: '';
        $task->type = $type;
		if ($type == 'cancel') {
			$task->description = Input::get('description');
			foreach ($contact->tasks as $t) {
				$t->completed = "yes";
				$t->save();
			}
			$activity = new ContactActivity();
			$activity->type = 'Добавлен комментарий';
			$activity->text = Input::get('description');
			$activity->contact_id = $contact->id;
			$activity->user_id = $user_id;
			$activity->save();
			$deadline = Carbon::now();
		}
        $task->deadline = Carbon::parse($deadline)->format('Y-m-d H:i');

        $task->user_id = $user_id;
		$task->income_id = 0;
        $task->author_id = $user_id;
        $task->contact_id = $contact->id;
		if ($department)
			$task->department_id = $department->id;
		else
			$task->department_id = 0;
		
        $task->save();

        if ( $task->type == 'appointment' )
        {
            $contact = $task->contact;
            if ( $contact && $contact->phone )
            {
				
                $text = 'Вам назначена встреча по адресу '.$task->user->department->address.' на '.$task->deadline.' Менеджер '.$task->user->name.', тел. '.$task->user->phone_work;
				$message = [
					'text' => $text
				];
				
				$contact->notify(new MessageNotification($message));
            }
        }
        return view('contacts.task.view', ['task' => $task, 'user_id' => $user_id]);
    }
	
	public function taskAppointmentCompleted()
    {
		$id = Input::get('task_id');
		$complete_status = Input::get('complete_status');
		$deadline = Input::get('deadline');
		
		$task = Task::findOrFail($id);
		
		if($complete_status == 'complete'){
			$task->completed = 'yes';
		}
		elseif($complete_status == 'canceled'){
			$task->completed = 'canceled';
		}
		elseif($complete_status == 'move'){
			$task->deadline = Carbon::parse($deadline)->format('Y-m-d H:i');
		}
		
		$task->income_id = 0;
			
        $task->save();
		
		$user_id = \Auth::user()->id;
		
        return view('contacts.task.view', ['task' => $task, 'user_id' => $user_id]);
    }
	
	public function taskCanceled($id = null)
    {
		$task = Task::findOrFail($id);
		$task->completed = 'no';
		$task->income_id = 0;
		$task->save();
		
		$user_id = \Auth::user()->id;
		
        return view('contacts.task.view', ['task' => $task, 'user_id' => $user_id]);
    }

    public function taskCompleted($id = null)
    {
		if($id){
			$task = Task::findOrFail($id);
			$task->completed = 'yes';
		} else {
			$id = Input::get('task_id');
			$task = Task::findOrFail($id);
			$task->completed = 'yes';
			$task->income_id = Input::get('income_id');
		}
        $task->save();
		
		$user_id = \Auth::user()->id;
		
        return view('contacts.task.view', ['task' => $task, 'user_id' => $user_id]);
    }

    public function show($id)
    {
        $contact = Contact::with(['user', 'data', 'activities.user', 'leads', 'tasks'])->findOrFail($id);
		$department = Department::findOrFail($contact->user->department_id);;
        return view('contacts.view', compact('contact', 'title', 'department'));
    }

    public function activity()
    {
        $offset = Input::get('offset', 5);
        $contactId = Input::get('contact_id', null);

        $activities = ContactActivity::whereContactId($contactId)->orderBy('updated_at', 'desc')->offset($offset)->limit(5)->get();

        $response = '';
        foreach ($activities as $activity)
        {
            $response .= view('contacts.view.activity.item', ['activity' => $activity])->render();
        }

        return $response;
    }

    public function addComment($id)
    {
        $contact = Contact::findOrFail($id);

        $activity = new ContactActivity();
        $activity->type = ContactActivity::TYPES['comment'];
        $activity->text = Input::get('comment');
        $activity->user_id = \Auth::user()->id;

        $contact->activities()->save($activity);

        return redirect(Session::get('contactRedirect', '/contacts#contact'.$contact->id));
    }

    public function addContact()
    {
        $contact = new Contact();
        $contact->name = Input::get('name');
        $contact->phone = Input::get('phone');
        $contact->email = Input::get('email');
        $contact->user_id = \Auth::user()->id;
        $contact->save();

        return $contact;
    }

    public function addLead($id)
    {
        $contact = Contact::with(['user', 'data', 'activities.user', 'leads', 'tasks'])->findOrFail($id);

        $service_id = Input::get('service_id');
        $service = LeadService::findOrFail($service_id);
		
		if (! Auth::user()->isAdmin()) {
			$department = Department::findOrFail(Auth::user()->department_id);
		} else {
			$department = null;
		}

        $lead = new Lead(); // @todo: check permission
        $lead->status_id = Input::get('status', LeadStatus::getDefaultStatus());
        $lead->user_id = $contact->user->id;
        $lead->department_id = $contact->user->department_id;
        $lead->contact_id = $contact->id;
        //$lead->name = 'Сделка №'. Input::get('number', 1);
		$lead->name = Input::get('name');
        $lead->service_id = $service_id;
		if ($department)
			$lead->city = $department->city;
		else
			$lead->city = 0;
        $lead->number = Input::get('number');
        $lead->budget = $service->cost;
        $lead->save();

        $lead->createTaskForService();

        return view('contacts.lead.view', ['_lead' => $lead]);
    }


    public function updatePayment()
    {
        $leadId = Input::get('lead_id');
        $paymentId = Input::get('payment_id');


        $lead = Lead::findOrFail($leadId);
		$user = User::findOrFail($lead->user_id);

        if ( $paymentId )
        {
            $payment = $lead->tasks()->whereId($paymentId)->first();
        }
        else
        {
            $payment = new Task();
            $payment->lead_id = $leadId;
        }

        $payment->name = 'Подтвердить оплату по сделки №'.$lead->number;
        $payment->type = 'approved_payment';

        $payment->user_id = $lead->user_id;
        $payment->contact_id = $lead->contact_id;
        $payment->author_id = $lead->user_id;
		$payment->department_id = $user->department_id;


        $payment->cost = Input::get('price');
		$payment->income_id = 0;
        $payment->deadline = Carbon::parse(Input::get('payment-date'))->format('Y-m-d H:i');

        $payment->save();

        $payments = $lead->tasks()->whereType('approved_payment')->orderBy('deadline', 'ASC')->get();

        $content = '';
        foreach ($payments as $_payment)
        {
            $content .= view('contacts.lead.payment', ['_payment' => $_payment, 'leadId' => $leadId, '_lead' => $lead])->render();
        }

        return $content;
    }

    // @todo: check permissions
    public function taskDelete($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return json_encode(['status' => 'ok']);
    }

	public function getIdFromJs($id)
	{
		// return $id;
		$contact = Contact::findOrFail($id);
		if ($contact->leads->count() > 0 || $contact->tasks->where('type', '!=', 'make_appointment')->count() > 0)
			return 1;
		else
			if ($contact->user_id == Auth::user()->id)
				return 0;
			else
				return 1;
		// return $id;
	}
}