<div id="tab-3" class="tab-pane">
    <div class="panel-body">
        @include('analytics.filters')

        <div class="panel panel-default">
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>ФИО</th>
						<th>Логин</th>
                        <th>Новых сделок</th>
                        <th>Прибыль</th>
                        <th>План</th>
                        <th>Планируемая прибыль</th>
                        <th>Доход менеджера</th>
                        <th>Просроченных задач</th>
                        <th>Выполненных задач</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php

                        $sumProfit = 0;
                        $sumRevenue = 0;
                        $sumNewLead = 0;
                        $sumPlannedProfit = 0;
                        $sumProfitManager = 0;
                        $sumDeadlineTask = 0;
                        $sumCompletedTask = 0;
						$last_login = 'Нет';
                    ?>
                    @foreach(\App\User::filterDepartment($departmentId)->filterTeam($teamId)->get() as $user)

                        <?php
						 // if ($user->blocked !== 1) {
                            $profit = $user->getProfit($departmentId, $dateStart, $dateEnd);
                            $percentRevenue = 0;
                            if ( $user->revenue > 0 && $profit > 0 )
                            {
                                $percentRevenue = ceil($profit / $user->revenue * 100);
                            }
                            $newLead = \App\Lead::whereUserId($user->id)->filterDepartment($departmentId)->filterTeam($teamId)->where('created_at', '>=', $dateStart)->where('created_at', '<=', $dateEnd)->count();
                            $plannedProfit = $user->getPlannedProfit($departmentId, $dateStart, $dateEnd);
                            $profitManager = $user->getProfitManager($departmentId, $dateStart, $dateEnd);
                            $deadlineTasks = \App\Task::whereUserId($user->id)->where('deadline', '>=', $dateStart)->where('deadline', '<=', $dateEnd)->where('updated_at', '<', DB::raw('`deadline`'))->count();
                            $completedTask = \App\Task::whereUserId($user->id)->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->where('completed', '=', 'yes')->count();
                            $sumProfit += $profit;
                            $sumNewLead += $newLead;
                            $sumRevenue += $user->revenue;
                            $sumPlannedProfit += $plannedProfit;
                            $sumProfitManager += $profitManager;
                            $sumDeadlineTask += $deadlineTasks;
                            $sumCompletedTask += $completedTask;
							if (($user->date !== NULL) && (strtotime($user->date) >= strtotime($dateStart)) && (strtotime($user->date) <= strtotime($dateEnd)))
								$last_login = 'Да';
							else
								$last_login = 'Нет';
						 // }
                        ?>
						{{-- @if ($user->blocked !== 1) --}}
                        <tr @if($user->blocked == 1) style="display: none;" @endif>
                            <td><a href="{{route('users.view', ['userId' => $user->id])}}"> {{$user->name}}</a></td>
							<td>{{$last_login}}</td>
                            <td>{{$newLead}}</td>
                            <td>{{$profit}}</td>
                            <td><b>{{$percentRevenue}}%</b> ({{$user->revenue}})</td>
                            <td>{{$plannedProfit}}</td>
                            <td>{{$profitManager}}</td>
                            <td>{{$deadlineTasks}}</td>
                            <td>{{$completedTask}}</td>
                        </tr>
						{{-- @endif --}}

                    @endforeach
                    <tr style=" font-weight: bold;">
                        <td>Итог</td>
						<td></td>
                        <td>{{$sumNewLead}}</td>
                        <td>{{$sumProfit}}</td>
                        <?php
                            $sumPercentRevenue = 0;
                            if ( $sumRevenue > 0 && $sumProfit > 0 )
                            {
                                $sumPercentRevenue = ceil($sumProfit / $sumRevenue * 100);
                            }
                        ?>
                        <td>{{$sumPercentRevenue}}% ({{$sumRevenue}})</td>
                        <td>{{$sumPlannedProfit}}</td>
                        <td>{{$sumProfitManager}}</td>
                        <td>{{$sumDeadlineTask}}</td>
                        <td>{{$sumCompletedTask}}</td>
                    </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
