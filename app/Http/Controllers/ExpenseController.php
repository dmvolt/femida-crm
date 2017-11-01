<?php

namespace App\Http\Controllers;

use App\Department;
use App\Expense;
use App\Cost;

use Illuminate\Http\Request;

use App\Http\Requests;
use Input;
use Zofe\Rapyd\DataGrid\DataGrid;

class ExpenseController extends Controller
{
    public $currentMenuId = 'expenses';

    public function index()
    {
        $this->authorize('show', new Expense());

        //$filter = \DataFilter::source(Expense::with(['user', 'department']));
		$expenses = Expense::with(['user', 'department', 'cost']);
		
		if (! \Auth::user()->isAdmin()) {
			$expenses = $expenses->where('department_id', '=', \Auth::user()->department_id);
		}
		
		$filter = \DataFilter::source($expenses);

        $filter->add('topSearch','Поиск', 'text')->scope('topSearch');
        $filter->add('updated_at','Дата','\App\Rapyd\Fields\MyDateRange')->format('Y-m-d', 'ru');
        $filter->submit('Фильтровать');
        $filter->reset('Сбросить');
        $filter->build();

        $grid = DataGrid::source($filter);
        $grid->attributes(["class"=>"table table-striped table-hover"]);

        $grid->add('id','ID', true)->style("width:100px");
		$grid->add('cost.name', 'Статья расходов');
        $grid->add('name','Коментарий');
        $grid->add('sum','Сумма');

        $grid->add('{{$user->name}}','Пользователь');
        $grid->add('{{$department->name}}','Филиал');
        $grid->add('fileUrl','Файл');
        $grid->add('updated_at','Дата');
        $grid->add('updated_at','Дата');

        $grid->edit(route('expenses.store'), 'Редактировать','modify|delete')->style("width:15px");
        $grid->link(route('expenses.store'), "Добавить", "TR");

        $grid->row(function ($row)  {
            $row->cell('fileUrl')->value($row->data->fileUrl);
        });

        $grid->orderBy('id','desc');
        $grid->paginate(50);

        $title = 'Расходы';
        $resultSum = $grid->source->source->sum('sum');

        return view('expenses', compact('content', 'grid',  'title', 'resultSum', 'filter'));
    }

    public function store($id = null)
    {
        if ( $deleteId = Input::get('delete', null) )
        {
            $expense = Expense::findOrFail($deleteId);
            $expense->delete();

            return redirect(route('expenses'));
        }

        $id = Input::get('modify', null) ?: $id;

        $isNew = $id == null;
        $source = $isNew ? new Expense() : Expense::find($id) ;

        $store = \DataForm::source($source);
		
		$store->add('cost_id', 'Статья расходов', 'select')->options(Cost::allForSelect());
		
        $store->add('name', 'Коментарий', 'text')->rule('required|min:2');
        $store->add('sum', 'Сумма', 'number')->rule('required|min:2');
        $store->add('file', 'Файл', 'file')->move('uploads/');
		if (! \Auth::user()->isAdmin()) 
		{
			$store->add('department_id','Филиал', 'select')->options([\Auth::user()->department_id => Department::findOrFail(\Auth::user()->department_id)->name])->attributes(['readonly' => '']);
		} else {
			$store->add('department_id','Филиал', 'select')->options(Department::all()->pluck('name', 'id'));
		}
		

        $store->submit('Сохранить');

        $store->model->user_id = \Auth::user()->id;
        $store->saved(function () use ($store) {
            return redirect(route('expenses', ['expenseId' => $store->model->id]));
        });

        $store->build();

        $title = $isNew ? 'Новый расход' : 'Редактирование';
        return $store->view('default.store', compact('store', 'title'));
    }
}
