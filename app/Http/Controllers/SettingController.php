<?php

namespace App\Http\Controllers;

use App\ContactOrigin;
use App\Department;
use App\LeadService;
use App\LeadStatus;
use App\Cost;
use App\Income;
use App\ServicePayment;
use App\Team;
use App\User;
use App\Task;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

use App\Http\Requests;
use Input;
use Zofe\Rapyd\DataGrid\DataGrid;

class SettingController extends Controller
{
    public $currentMenuId = 'settings';

    public function __construct()
    {
        parent::__construct();

        $this->middleware('settingsMiddleware');
    }

    public function index()
    {
        $users = $this->getUserContent();
        $departments = $this->getDepartmentContent();
        $services = $this->getServiceContent();
        $statuses = $this->getStatusesContent();
        $teams = $this->getTeamsContent();
        $origins = $this->getOriginsContent();
		
		$costs = $this->getCostsContent();
		$incomes = $this->getIncomesContent();

        return view('setting', compact('users', 'departments', 'services', 'statuses', 'teams', 'origins', 'costs', 'incomes'));
    }

    /**
     * @param Request $request
     * @param integer $id
     * @return \Zofe\Rapyd\DataForm\Redirect|\Zofe\Rapyd\DataForm\View
     */
    public function userStore(Request $request, $id = null)
    {
		$changePassword = false;
		
        if ( $id == null ) $id = Input::get('modify', null);
		
        $source = $id ? User::find($id) : new User();
		
        $store = \DataForm::source($source);
		
        $store->add('email','E-mail', 'text')->rule('required|min:5');
        $store->add('name','Имя', 'text')->rule('required|min:2');
		
		if ($id && $request->isMethod('post'))
        {
			if( Input::get('password') != '' )
            {
                $store->add('password','Пароль', 'password')->rule('required|min:5');
                $changePassword = true;
            }
			
			if(Input::get('role_id') && $id != \Auth::user()->id)
            {
                $store->add('role_id','Роль пользователя', 'select')->options(User::$roles)->attributes(((!\Auth::user()->isDepLeader() && !\Auth::user()->isAdmin()) ? ['disabled'] : []));
            }
			
            if(Input::get('department_id') && $id != \Auth::user()->id)
            {
                $store->add('department_id','Филиал', 'select')->options(Department::all()->pluck('name', 'id'))->attributes(((!\Auth::user()->isDepLeader() && !\Auth::user()->isAdmin()) ? ['disabled'] : []));
            }
			
			if(Input::get('team_id') && $id != \Auth::user()->id)
            {
                $store->add('team_id','Отдел', 'select')->options(Team::all()->pluck('name', 'id'))->attributes(((!\Auth::user()->isDepLeader() && !\Auth::user()->isAdmin()) ? ['disabled'] : []));
            }
        }
        else
        {
			$store->add('password','Пароль', 'password')->rule('required|min:5');
            
			if(\Auth::user()->isDepLeader())
			{
				$store->add('role_id','Роль пользователя', 'select')->options(User::$depleader_roles);
			}
			elseif(\Auth::user()->isAdmin())
			{
				$store->add('role_id','Роль пользователя', 'select')->options(User::$roles);
			}
			
			if(\Auth::user()->isDepLeader())
			{
				$store->add('department_id','Филиал', 'select')->options(Department::all()->where('id', '=', \Auth::user()->department_id)->pluck('name', 'id'));
			}
			elseif(\Auth::user()->isAdmin())
			{
				$store->add('department_id','Филиал', 'select')->options(Department::all()->pluck('name', 'id'));
			}
			
			$store->add('team_id','Отдел', 'select')->options(Team::all()->pluck('name', 'id'))->attributes(((!\Auth::user()->isDepLeader() && !\Auth::user()->isAdmin()) ? ['disabled'] : []));
        }
		
        $store->add('phone','Телефон', 'text');
        $store->add('phone_work','Рабочий телефон', 'text');
        $store->add('bonus','Процент от сделок', 'text')->rule('required');
        $store->add('revenue','Месячный план продаж', 'text')->rule('required|int');
        $store->add('blocked','Заблокирован', 'checkbox');
		
		$store->add('filename', 'Картинка', 'image')
			->move('uploads/images/users/')
			->preview(100, 100);

        $store->add('number','Серия и номер', 'text');
        $store->add('code','Код', 'text');
        $store->add('issued','Выдан', 'text');
        $store->add('address','Адрес', 'text');
        $store->add('date','Дата', 'date');

        $store->submit('Сохранить');
		
        $store->saved(function () use ($store, $changePassword) 
		{
			if ($store->model->blocked == 1)
			{
				$depLeader = User::all()->where('department_id', '=', $store->model->department_id)->where('role_id', '=', '4')->first();
				
				foreach($store->model->tasks as $task) 
				{
					if (!$task->isComleted()) {
						$task->user()->dissociate($store->model);
						$task->user()->associate($depLeader);
						$task->save();
					}
				}
			}
            if ( $changePassword )
            {
                $store->model->password = \Hash::make($store->model->password);
            }
			
			/*********************************** IMAGE ************************************/
			if($store->model->filename && file_exists(base_path().'/public_html/uploads/images/users/'.$store->model->filename) && !file_exists(base_path().'/public_html/uploads/images/users/100x100/'.$store->model->filename)){
				
				// open an image file
				$img = Image::make('uploads/images/users/'.$store->model->filename);

				// resize the image to a width of 300 and constrain aspect ratio (auto height)
				$img->resize(100, null, function ($constraint) {
					$constraint->aspectRatio();
				});
				
				// crop image
				$img->crop(100, 100, null, 10);

				
				// finally we save the image as a new file
				$img->save('uploads/images/users/100x100/'.$store->model->filename);
			}
			/********************************** /IMAGE ************************************/

            $store->model->save();
            return redirect('/settings');
        });

        $store->build();

        $title = 'Новый контакт';
        return $store->view('default.store', compact('store', 'title'));
    }

    /**
     * @param integer $id
     * @return \Zofe\Rapyd\DataForm\Redirect|\Zofe\Rapyd\DataForm\View
     */
    public function serviceStore(Request $request, $id = null)
    {

        if ( $id == null ) $id = \Input::get('modify', null);
        $source = $id ? LeadService::find($id) : new LeadService();

        $store = \DataForm::source($source);

        $store->add('name','Название', 'text')->rule('required|min:2');
        $store->add('cost','Стоимость', 'text');

        $store->submit('Сохранить');

        $store->saved(function () use ($store, $request) {
            if ($request->isMethod('post'))
            {
                $store->model->payments()->delete();
                $countPayments = count(Input::get('payments.days', []));

                $days = Input::get('payments.days', []);
                $cost = Input::get('payments.cost', []);

                $payments = [];

                for($i = 0; $i < $countPayments; $i++)
                {
                    $payment = new ServicePayment();

                    $payment->days = $days[$i];
                    $payment->cost = $cost[$i];

                    $payments[] = $payment;
                }
                $store->model->payments()->saveMany($payments);
            }

            return redirect('/settings');
        });

        $store->build();

        $title = 'Новая услуга';
        return $store->view('settings.services.store', compact('store', 'title'));
    }

    public function departmentStore($id = null)
    {
        if ( $id == null ) $id = \Input::get('modify', null);
        $source = $id ? Department::find($id) : new Department();

        $store = \DataForm::source($source);
        $store->add('name', 'Имя', 'text')->rule('required|min:2');
        $store->add('address', 'Адрес', 'text')->rule('required|min:2');
		$store->add('city', 'Город', 'text')->rule('required|min:2');

        $store->submit('Сохранить');

        $store->saved(function () use ($store) {
            return redirect('/settings');
        });

        $store->build();

        $title = 'Новый филиал';
        return $store->view('default.store', compact('store', 'title'));
    }

    public function teamStore($id = null)
    {
        if ( $id == null ) $id = \Input::get('modify', null);
        $source = $id ? Team::find($id) : new Team();

        $store = \DataForm::source($source);
        $store->add('name','Имя', 'text')->rule('required|min:2');
        $store->add('department_id','Филиал', 'select')->options(Department::all()->pluck('name', 'id'));
        $store->add('revenue','Месячный план продаж', 'text')->rule('required|int');

        $store->submit('Сохранить');

        $store->saved(function () use ($store) {
            return redirect('/settings');
        });

        $store->build();

        $title = 'Отделы';
        return $store->view('default.store', compact('store', 'title'));
    }

    public function statusStore($id = null)
    {
        if ( $id == null ) $id = \Input::get('modify', null);
        $source = $id ? LeadStatus::find($id) : new LeadStatus();

        $store = \DataForm::source($source);
        $store->add('name','Имя', 'text')->rule('required|min:2');

        $store->add('color','Цвет', 'select')->options(LeadStatus::$colorNames);
        $store->add('type','Тип статуса', 'select')->options(LeadStatus::$typeNames);

        $store->submit('Сохранить');

        $store->saved(function () use ($store) {
            return redirect('/settings');
        });

        $store->build();

        $title = 'Статус';
        return $store->view('default.store', compact('store', 'title'));
    }
	
	public function costStore($id = null)
    {
        if ( $id == null ) $id = \Input::get('modify', null);
        $source = $id ? Cost::find($id) : new Cost();

        $store = \DataForm::source($source);
        $store->add('name','Наименование', 'text')->rule('required|min:2');

        $store->add('color','Цвет', 'select')->options(Cost::$colorNames);
        $store->add('type','Группа', 'select')->options(Cost::$typeNames);

        $store->submit('Сохранить');

        $store->saved(function () use ($store) {
            return redirect('/settings');
        });

        $store->build();

        $title = 'Типы расходов';
        return $store->view('default.store', compact('store', 'title'));
    }
	
	public function incomeStore($id = null)
    {
        if ( $id == null ) $id = \Input::get('modify', null);
        $source = $id ? Income::find($id) : new Income();

        $store = \DataForm::source($source);
        $store->add('name','Наименование', 'text')->rule('required|min:2');
        $store->add('color','Цвет', 'select')->options(Income::$colorNames);
        $store->submit('Сохранить');

        $store->saved(function () use ($store) {
            return redirect('/settings');
        });

        $store->build();

        $title = 'Типы доходов';
        return $store->view('default.store', compact('store', 'title'));
    }

    public function getUserContent()
    {
        $source = new User();

        // @todo: check permission
        if ( \Auth::user()->isDepLeader() )
        {
            $source = $source->whereDepartmentId(\Auth::user()->department_id);
        }
		
		if ( Input::get('banned', null) == null )
        {
            $source = $source->whereBlocked(0);;
        }

        $filter = \DataFilter::source($source);
        $filter->add('topSearch','Поиск', 'text')->scope('topSearch');

        $userTypes = ['active' => 'Не заблокированные', 'banned' => 'Заблокированные', 'all' => 'Все пользователи'];
        $filter->add('banned','Тип', 'select')->options($userTypes)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Тип'])->scope('typeUser');

        $roles = [null => 'Все роли'] + User::$roles;
        $filter->add('role_id','Роль', 'select')->options($roles)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Роль']);
		
		if ( \Auth::user()->isAdmin() )
		{
			$departments = [null => 'Все филиалы'] + Department::all()->pluck('name', 'id')->toArray();
			$filter->add('department_id', 'Филиал', 'select')->options($departments)->attributes(['class' => 'chosen-select-200px', 'data-placeholder' => 'Филиалы']);
		}

        $filter->submit('Фильтровать');
        $filter->reset('Сбросить');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->attributes(["class"=>"table table-striped table-bordered table-hover dataTables-example"]);

        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','ФИО');
        $grid->add('email','E-mail');
        $grid->add('phone','Телефон');
        $grid->add('phone_work','Рабочий');

        $grid->edit(route('setting.user.store'), 'Редактировать','modify')->style("width:15px");
        $grid->link(route('setting.user.store'), "Новый пользователь", "TR");

        $grid->row(function ($row) {
            $url = route('users.view', ['userId' => $row->data->id]);
            $name = $row->cell('name')->value;

            $row->cell('name')->value('<a class="client-link" href="'.$url.'">'.$name.'<a/>');
        });

        $grid->orderBy('id','desc');
        $grid->paginate(100);

        $title = 'Контакты';
        return view('default.grid', compact('grid', 'title', 'filter'));
    }

    public function getDepartmentContent()
    {
        $grid = DataGrid::source(new Department());
        $grid->attributes(["class"=>"table table-striped table-bordered table-hover dataTables-example"]);

        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','ФИО');
        $grid->add('address','Адрес');

        $grid->edit(route('setting.department.store'), 'Редактировать','modify')->style("width:15px");
        $grid->link(route('setting.department.store'), "Новый филиал", "TR");

        $grid->orderBy('id','desc');
        $grid->paginate(100);

        $title = 'Филиалы';
        return view('default.grid', compact('grid', 'title'));
    }

    public function getServiceContent()
    {
        $grid = DataGrid::source(new LeadService());
        $grid->attributes(["class"=>"table table-striped table-bordered table-hover dataTables-example"]);

        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','Название');

        $grid->edit(route('setting.service.store'), 'Редактировать','modify')->style("width:15px");
        $grid->link(route('setting.service.store'), "Новая услуга", "TR");

        $grid->orderBy('id','desc');
        $grid->paginate(100);

        $title = 'Услуги';
        return view('default.grid', compact('grid', 'title'));
    }

    public function getStatusesContent()
    {
        $grid = DataGrid::source(new LeadStatus());
        $grid->attributes(["class"=>"table table-striped table-bordered table-hover dataTables-example"]);

        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','Название');

        $grid->edit(route('setting.status.store'), 'Редактировать','modify')->style("width:15px");
        $grid->link(route('setting.status.store'), "Новый статус", "TR");

        $grid->orderBy('id','desc');
        $grid->paginate(100);

        $title = 'Статусы';
        return view('default.grid', compact('grid', 'title'));
    }
	
	public function getCostsContent()
    {
        $grid = DataGrid::source(new Cost());
        $grid->attributes(["class"=>"table table-striped table-bordered table-hover dataTables-example"]);

        $grid->add('id','ID', true)->style("width:100px");
		
		$grid->add('color','Цвет')->cell( function($value, $row) {
			return '<span class="badge" style="background:'.$value.';color:#fff;">'.Cost::$colorNames[$value].'</span>'; //Cost::$colorNames[$value]
		});
		
		$grid->add('type','Группа')->cell( function($value, $row) {
			return Cost::$typeNames[$value];
		});
		
        $grid->add('name','Название');

        $grid->edit(route('setting.cost.store'), 'Редактировать','modify')->style("width:15px");
        $grid->link(route('setting.cost.store'), "Новый вид расходов", "TR");

        $grid->orderBy('id');
        $grid->paginate(100);

        $title = 'Виды расходов';
        return view('default.grid', compact('grid', 'title'));
    }
	
	public function getIncomesContent()
    {
        $grid = DataGrid::source(new Income());
        $grid->attributes(["class"=>"table table-striped table-bordered table-hover dataTables-example"]);

        $grid->add('id','ID', true)->style("width:100px");
		
		$grid->add('color','Цвет')->cell( function($value, $row) {
			return '<span class="badge" style="background:'.$value.';color:#fff;">'.Cost::$colorNames[$value].'</span>'; //Income::$colorNames[$value]
		});
		
        $grid->add('name','Название');

        $grid->edit(route('setting.income.store'), 'Редактировать','modify')->style("width:15px");
        $grid->link(route('setting.income.store'), "Новый вид доходов", "TR");

        $grid->orderBy('id');
        $grid->paginate(100);

        $title = 'Виды доходов';
        return view('default.grid', compact('grid', 'title'));
    }

    public function getTeamsContent()
    {
        $grid = DataGrid::source(new Team());
        $grid->attributes(["class"=>"table table-striped table-bordered table-hover dataTables-example"]);

        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','ФИО');

        $grid->edit(route('setting.teams.store'), 'Редактировать','modify')->style("width:15px");
        $grid->link(route('setting.teams.store'), "Новый отдел", "TR");

        $grid->orderBy('id','desc');
        $grid->paginate(100);

        $title = 'Отделы';
        return view('default.grid', compact('grid', 'title'));
    }

    public function getOriginsContent()
    {
        $grid = DataGrid::source(new ContactOrigin());
        $grid->attributes(["class"=>"table table-striped table-bordered table-hover dataTables-example"]);

        $grid->add('id','ID', true)->style("width:100px");
        $grid->add('name','Название');

        $grid->edit(route('setting.origin.store'), 'Редактировать','modify')->style("width:15px");
        $grid->link(route('setting.origin.store'), "Новый источник", "TR");

        $grid->orderBy('id','desc');
        $grid->paginate(100);

        $title = 'Источники';
        return view('default.grid', compact('grid', 'title'));
    }

    public function originStore($id = null)
    {
        if ( $id == null ) $id = \Input::get('modify', null);
        $source = $id ? ContactOrigin::find($id) : new ContactOrigin();

        $store = \DataForm::source($source);
        $store->add('name','Имя', 'text')->rule('required|min:2');
        $store->submit('Сохранить');

        $store->saved(function () use ($store) {
            return redirect('/settings');
        });

        $store->build();

        $title = 'Статус';
        return $store->view('default.store', compact('store', 'title'));
    }

}
