<?php

namespace App\Http\Controllers;

use App\Expense;
use App\Cost;
use App\Income;
use App\Lead;
use App\LeadStatus;
use App\Task;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use Input;

class AnalyticsController extends Controller
{
    public $currentMenuId = 'analytics';

    public function index()
    {
        //@todo refactor all category
        Carbon::setLocale(config('app.locale'));

        $user = \Auth::user();

        $canChangeTeam = true;
        $canChangeDepartment = true;

        $departmentId = Input::get('department_id', null);
        $teamId = Input::get('team_id', null);

        if ( ! $user->isAdmin() )
        {
            $canChangeDepartment = false;
            $departmentId = \Auth::user()->department_id;

            if ( ! $user->isDepLeader() )
            {
                $canChangeTeam = false;
                $teamId = \Auth::user()->team_id;
            }

        }

        $dateEndString = Input::get('dateEnd', null);
        if ( $dateEndString )
        {
            $dateEnd = Carbon::parse($dateEndString)->addHours(23)->addMinute(59);
        }
        else
        {
            $dateEnd = Carbon::now()->addHours(23)->addMinute(59);
        }

        $dateStartString = Input::get('dateStart', null);
        if ( $dateStartString )
        {
            $dateStart = Carbon::parse($dateStartString);
        }
        else
        {
            $dateStart = Carbon::now()->subDays(30);
        }
		
		$allCosts = [];
		
		foreach(Cost::$typeNames as $typeId => $typeName){
			$allCosts[$typeId] = Cost::where('type', '=', $typeId)->get();
		}
		
		$incomes = Income::all();

        $generalChartAll = [];
		$generalChartIncome = [];
		$generalChartCost = [];
		
		$expenses_arr = [];
		$profit_arr = [];
		
		$profit_all = 0;
		$expenses_all = 0;
		
        $charDate = $dateStart->copy();
		
		for( $i =0; $i <= $dateEnd->diffInMonths($dateStart); $i++)
		{
			$name = $charDate->format('F');
			
			$profit_all = 0;
			$expenses_all = 0;
			
			$expenses_arr = [];
			$profit_arr = [];

			$start = $charDate->toDateTimeString();
			if ($dateEnd->diffInMonths($charDate) > 0) {
				$end = $charDate->addMonth()->toDateTimeString();
			} else {
				$end = $charDate->addDays($dateEnd->diffInDays($charDate));
			}
			 
			if(!empty($allCosts)){
				foreach($allCosts as $costType => $costs){
					
					if($costs){
						
						$expenses_sum = 0;
						
						foreach($costs as $cost){
							//$expenses = Expense::where('updated_at', '>=', $dateStart)
							//->where('updated_at', '<=', $dateEnd)
							
							$expenses = Expense::where('updated_at', '>=', $start)
							->where('updated_at', '<=', $end)
							->where('cost_id', '=', $cost->id);

							if($departmentId)
							{
								$expenses->whereDepartmentId($departmentId);
							}
							
							$expenses_sum += $expenses->sum('sum');
							$expenses_all += $expenses->sum('sum');
						}
						$expenses_arr['c'.$costType] = $expenses_sum;
					}
				}
			}

			if($incomes){
				foreach($incomes as $income){
					//$profit = Task::withPayments()->where('updated_at', '>=', $dateStart)
					//->where('updated_at', '<=', $dateEnd)
					
					$profit = Task::withPayments()->where('updated_at', '>=', $start)
					->where('updated_at', '<=', $end)
					->where('income_id', '=', $income->id)
					->where('completed', '=', 'yes');

					if ($departmentId)
					{
						$userIds = User::whereDepartmentId($departmentId)->pluck('id')->toArray();

						$profit = $profit->where(function($query) use($userIds) {
						   $query->whereIn('user_id', $userIds)->orWhereIN('author_id', $userIds);
						   return $query;
						});
					/*	
						$expenses = $expenses->where(function($query) use($userIds) {
						   $query->whereIn('user_id', $userIds);//->orWhereIN('author_id', $userIds);
						   return $query;
						});
					*/
					}

					if ($teamId)
					{
						$userIds = User::whereTeamId($teamId)->pluck('id')->toArray();

						$profit = $profit->where(function($query) use($userIds) {
							$query->whereIn('user_id', $userIds)->orWhereIN('author_id', $userIds);

						   return $query;
						});
						/*
						$expenses = $expenses->where(function($query) use($userIds) {
						   $query->whereIn('user_id', $userIds)->orWhereIN('author_id', $userIds);
						   return $query;
						});
						*/
					}

					//$expenses = $expenses->sum('sum');
					$profit_arr['i'.$income->id] = $profit->sum('cost');
					$profit_all += $profit->sum('cost');
				}
			}
			
			$generalChartAll[] = [
				'y' => $name,
				'a' => $profit_all,
				'b' => $expenses_all,
			];
			
			$array1 = [
				'y' => $name,
			];
			
			$generalChartIncome[] = $array1 + $profit_arr;
			
			$generalChartCost[] = $array1 + $expenses_arr;
		}
		
		$chartKeysAll = "['a', 'b']";
		$chartLabelsAll = "['Доходы', 'Расходы']";
		$chartColorsAll = "['#28a745', '#dc3545']";
		
		$chartKeysIncome = "[";
		$chartLabelsIncome = "[";
		$chartColorsIncome = "[";
		
		$chartKeysCost = "[";
		$chartLabelsCost = "[";
		$chartColorsCost = "[";
		
		if($incomes){
			foreach($incomes as $key => $income){
				if($key){
					$chartKeysIncome .= ", 'i".$income->id."'";
					$chartLabelsIncome .= ", 'Доходы ".$income->name."'";
					$chartColorsIncome .= ", '".$income->color."'";
				} else {
					$chartKeysIncome .= "'i".$income->id."'";
					$chartLabelsIncome .= "'Доходы ".$income->name."'";
					$chartColorsIncome .= "'".$income->color."'";
				}
			}
		}
		
		if(!empty($allCosts)){
			foreach(Cost::$typeNames as $typeId => $typeName){
				
				$costColor = (!empty($expenses_arr) && $expenses_arr['c'.$typeId] != 0) ? $allCosts[$typeId][0]->color : '#ccc';
				
				if($typeId != 'reklama'){
					$chartKeysCost .= ", 'c".$typeId."'";
					$chartLabelsCost .= ", 'Расходы ".$typeName."'";
					$chartColorsCost .= ", '".$costColor."'";
				} else {
					$chartKeysCost .= "'c".$typeId."'";
					$chartLabelsCost .= "'Расходы ".$typeName."'";
					$chartColorsCost .= "'".$costColor."'";
				}
			}
		}
		
		$chartKeysIncome .= "]";
		$chartLabelsIncome .= "]";
		$chartColorsIncome .= "]";
		
		$chartKeysCost .= "]";
		$chartLabelsCost .= "]";
		$chartColorsCost .= "]";

        $generalChartAll = collect($generalChartAll)->toJson();
		$generalChartCost = collect($generalChartCost)->toJson();
		$generalChartIncome = collect($generalChartIncome)->toJson();
		
        return view('analytics', compact(
			'dateEnd', 
			'dateStart', 
			'departmentId',
			
			'generalChartAll', 
			'chartKeysAll', 
			'chartLabelsAll', 
			'chartColorsAll',
			
			'generalChartCost', 
			'chartKeysCost', 
			'chartLabelsCost', 
			'chartColorsCost',
			
			'generalChartIncome', 
			'chartKeysIncome', 
			'chartLabelsIncome', 
			'chartColorsIncome',
			
			'teamId', 
			'canChangeTeam', 
			'canChangeDepartment'
		));
    }
}
