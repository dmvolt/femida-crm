<?php

namespace App\Http\Controllers;

use App\Expense;
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

        $generalChart = [];
        $charDate = $dateStart->copy();
        for( $i =0; $i <= $dateEnd->diffInMonths($dateStart); $i++)
        {
            $name = $charDate->format('F');

            $start = $charDate->toDateTimeString();
			if ($dateEnd->diffInMonths($charDate) > 0) {
				$end = $charDate->addMonth()->toDateTimeString();
			} else {
				$end = $charDate->addDays($dateEnd->diffInDays($charDate));
			}

            $expenses = Expense::where('updated_at', '>=', $start)->where('updated_at', '<=', $end);

            if ( $departmentId )
            {
                $expenses->whereDepartmentId($departmentId);
            }
            $expenses = $expenses->sum('sum');

            $profit = Task::withPayments()->where('updated_at', '>=', $start)->where('updated_at', '<=', $end)->where('completed', '=', 'yes');

            if ( $departmentId )
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

            if ( $teamId )
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
            $profit = $profit->sum('cost');

            $generalChart[] = [
                'y' => $name,
                'a' => $profit,
                'b' => $expenses,
            ];
        }

        $generalChart = collect($generalChart)->toJson();
        return view('analytics', compact('dateEnd', 'dateStart', 'departmentId', 'generalChart', 'teamId', 'canChangeTeam', 'canChangeDepartment'));
    }
}
