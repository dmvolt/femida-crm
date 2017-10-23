<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Department;
use App\Http\Traits\DefaultControllerTrait;
use App\Lead;
use App\LeadActivity;
use App\LeadService;
use App\LeadStatus;
use App\MyDateRange;
use App\User;
use Auth;
use DataGrid;
use DataFilter;
use Input;
use Response;
use Zofe\Rapyd\Helpers\HTML;

class LeadController extends Controller
{
    use DefaultControllerTrait;
    public $currentMenuId = 'leads';
	
	public function showDepartment($id)
	{
		$leads = Lead::with(['user', 'contact', 'status', 'service', 'department'])->allowedView();

        // по умолчанию показываем только открытые и сразу фильтруем по филиалу
        if ( Input::get('status_id', null) == null )
        {
            $leads = $leads->withStatusOpened();
        }
		


		if ($id != 0) {
			$leads = $leads->where('department_id', '=', $id);
		}
		
		/*
			вынесенный фильтр
		*/
		$deps = '';
		if (\Auth::user()->isAdmin()) {
			$departmentOptions = ['0' => 'Все филиалы'] + Department::all()->pluck('name', 'id')->toArray();
			foreach ($departmentOptions as $key => $value) {
				$style = ($key == $id) ? 'primary' : 'default';
				$deps .= '<a href="/leads/departments/'.$key.'" class="btn btn-'.$style.'" type="submit">'.$value.'</a></n>';
			}
		}


		/*
			фильтр без изменений
		*/
        $filter = \DataFilter::source($leads);
        $filter->add('topSearch','Поиск', 'text')->scope('topSearch');

        if ( ! \Auth::user()->isManager() )
        {
            $userOptions = [null => 'Все менеджеры'] + User::all()->pluck('name', 'id')->toArray();
            $filter->add('user_id','Менеджер', 'select')->options($userOptions)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Менеджер']);
        }

        $contactOptions = [null => 'Все контакты'] + Contact::all()->pluck('name', 'id')->toArray();
        $filter->add('contact_id','Контакт', 'select')->options($contactOptions)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Контакт']);

        $statusOptions = ['open' => 'Открытые', 'all' => 'Все (включая закрытые)'] + LeadStatus::all()->pluck('name', 'id')->toArray();
        $filter->add('status_id','Статус', 'select')->options($statusOptions)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Статус'])->scope('searchStatus');

        $serviceOptions = [null => 'Все услуги'] + LeadService::all()->pluck('name', 'id')->toArray();
        $filter->add('service_id','Услуга', 'select')->options($serviceOptions)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Услуга']);
	
		/*
				нерабочий фильтр по датам!!!!!!
		*/
	
		
        $filter->add('updated_at','Обновлено','\App\Rapyd\Fields\MyDateRange')->format('m/d/Y', 'ru');
		
		
		//$filter->add('updated_at','Обновлено','daterange')->format('m/d/Y', 'ru');
		
        $filter->submit('Фильтровать');
        $filter->reset('Сбросить');
        $filter->build();

        $leadsTable = DataGrid::source($filter);
		//$leadsTable = DataGrid::source($leads);
        $leadsTable->attributes(["class"=>"table table-striped table-hover"]);
		
        $leadsTable->add('name','Название')->style("width:100px");
        $leadsTable->add('{{$user->name or null}}', 'Ответственный');
        $leadsTable->add('{{$contact->name or null}}', 'Контакт');
		$leadsTable->add('{{$department->city or null}}', 'Город');
        $leadsTable->add('{{$service->name or null}}', 'Услуга');
        $leadsTable->add('budget', 'Стомость');
        $leadsTable->add('{{$status->name}}', 'Статус');
        $leadsTable->add('updated_at', 'Обновлено', true);

        $leadsTable->orderBy('id','desc');
        $leadsTable->paginate(15);

        if ( Auth::user()->isAdmin() )
        {
            $leadsTable->edit(route('leads.store'), 'Удалить','delete')->style("width:15px");
        }

		
        $leadsTable->row(function ($row) {
            $name = $row->cell('name')->value;
			$row->cell('name')->value('<a class="client-link" href="#" data-id="'.$row->data->contact_id.'" >'.$row->data->name.'<a/>');
            $row->cell('{{$status->name}}')->value('<span class="label label-'.$row->data->status->color.'">'.$row->data->status->name.'</span>');

        });

        $leadsBoard = [];
        $statuses = LeadStatus::all();
        foreach ($statuses as $status)
        {

            $leadsBoard[$status->id]['name'] =  $status->name;
            $query = clone $filter->query;
            $query = $query->where('status_id', '=', $status->id);

            $leadsBoard[$status->id]['leads'] = [];
            $leadsBoard[$status->id]['color'] = $status->color;
            foreach ($query->get() as $lead)
            {
                $leadsBoard[$status->id]['leads'][] =  $lead;
            }

        }
/*
		$mypar = Department::all()->pluck('city', 'id')->filter(function ($value, $key) {
				if ( ($key == Auth::user()->department_id) )
					return $value;
			})->first();
*/
		$mypar = '';
		//$dep = Department::findOrFail(Auth::user()->department_id);
		//$mypar = $dep->city;
		

        return view('lead', compact('leadsBoard', 'leadsTable', 'filter', 'deps', 'mypar'));
	}

    public function index()
    {
        return $this->showDepartment(0);
    }

    public function show($id)
    {
        $lead = Lead::with(['user', 'contact', 'status', 'service'])->findOrFail($id);
        $this->authorize('show', $lead);

        return view('leads.view', compact('lead'));
    }

    public function delete($id)
    {
        $lead = Lead::findOrFail($id);
        $this->authorize('delete', $lead);

        $lead->tasks()->delete();
        $lead->delete();
        return json_encode(['status' => 'ok']);
    }

    public function store($id = null)
    {
        if ( $redirect = $this->deleteAction(new Lead(), 'leads') )
        {
            return $redirect;
        }

        $source = $this->getSource($id, new Lead());

        $store = \DataForm::source($source);
        $store->add('name','Название', 'text')->rule('required|min:2');
        $store->add('status_id','Статус', 'select')->options(LeadStatus::all()->pluck('name', 'id'));

        $store->add('service_id','Услуга', 'select')->options(LeadService::all()->pluck('name', 'id'))->attributes(['class' => 'chosen-select']);
        $store->add('contact_id','Контакт', 'select')->options(Contact::all()->pluck('name', 'id'))->attributes(['class' => 'chosen-select-80']);

        $user = $store->add('user_id','Менеджер', 'select')->options(User::all()->pluck('name', 'id'))->attributes(['class' => 'chosen-select']);
        if ( ! $source->exists )
        {
            $user->value = \Auth::user()->id;
        }

        $store->add('budget','Бюджет сделки', 'number');
        $store->add('description','Описание', 'redactor');


        $this->preferValues($store);

        $store->submit('Сохранить');
        $store->saved(function () use ($store) {
            if ($store->action == 'insert')
            {
                $store->model->createTaskForService();
            }
            return redirect(route('leads.view', ['contactId' => $store->model->id]));
        });
        $store->build();

        $title = ! $source->exists ? 'Новая сделка' : 'Редактирование сделки';
        $services = LeadService::all()->pluck('cost', 'id')->toJson();
        return $store->view('leads.store', compact('store', 'title', 'services'));

    }

    public function addComment($id)
    {
        $lead = Lead::findOrFail($id);

        $activity = new LeadActivity();
        $activity->type = LeadActivity::TYPES['comment'];
        $activity->text = Input::get('comment');
        $activity->user_id = \Auth::user()->id;

        $lead->activities()->save($activity);

        return redirect(route('leads.view', ['lead' => $lead->id]));
    }

    public function activity()
    {
        $offset = Input::get('offset', 5);
        $leadId = Input::get('lead_id', null);

        $activities = LeadActivity::whereLeadId($leadId)->offset($offset)->limit(5)->get();

        $response = '';
        foreach ($activities as $activity)
        {
            $response .= view('leads.view.activity.item', ['activity' => $activity])->render();
        }

        return $response;
    }

    public function statusUpdate()
    {
        $leadId = Input::get('lead_id', null);
        $lead = Lead::findOrFail($leadId);
        $lead->status_id = Input::get('status_id', LeadStatus::getDefaultStatus());
        $lead->save();
    }


}
