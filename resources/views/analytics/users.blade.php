<div id="tab-3" class="tab-pane">
    <div class="panel-body">
        @include('analytics.filters')

        <div class="panel panel-default">
            <div class="panel-body" style="width: 100%; margin-left:0;">
			
				<table id="user_table" class="table table-hover tablesorter">
                    <thead>
                    <tr>
                        <th>ФИО <span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>План<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Логин<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Взятых заявок<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Назначено встреч<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Назначено звонок<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Брак<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Судебное заседание<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Проведено встреч<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Новых договоров<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Принято оплат<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Планируется доплат на сумму<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
						<th>Сумма фактически полученная<br><span class="glyphicon glyphicon-chevron-down"></span><span class="glyphicon glyphicon-chevron-up"></span></th>
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
						
						$sumAllTasks = 0;
						$sumAppointmentTasks = 0;
						$sumRecallTasks = 0;
						$sumCancelTasks = 0;
						$sumCourtHearingTasks = 0;
						$sumCompletedAppointmentTasks = 0;
						$sumPaymentCount = 0;
						
						$last_login = 'Нет';
                    ?>
                    @foreach(\App\User::filterDepartment($departmentId)->filterTeam($teamId)->get() as $user)

                        <?php
						if ($user->blocked !== 1) {
                            
                            //$profitManager = $user->getProfitManager($departmentId, $dateStart, $dateEnd);
							
							// всего задач
							$allTasks = \App\Task::whereUserId($user->id)->where(['type' => 'request'])->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->count();
							
							// назначеных встреч
							$appointmentTasks = \App\Task::whereUserId($user->id)->where(['type' => 'appointment'])->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->count();
							
							// звонок
							$recallTasks = \App\Task::whereUserId($user->id)->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->where(['type' => 'recall'])->count();
							
							// брак
							$cancelTasks = \App\Task::whereUserId($user->id)->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->where(['type' => 'cancel'])->count();
							
							// судебное заседание
							$courtHearingTasks = \App\Task::whereUserId($user->id)->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->where(['type' => 'court_hearing'])->count();
							
                            // фактически проведенных встреч
							$completedAppointmentTasks = \App\Task::whereUserId($user->id)->where(['type' => 'appointment', 'completed' => 'yes'])->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->count();
							
							// новых договоров
							$userLeads = \App\Lead::whereUserId($user->id)->filterDepartment($departmentId)->filterTeam($teamId)->where('created_at', '>=', $dateStart)->where('created_at', '<=', $dateEnd);
							$newLead = $userLeads->count();
							
							// Планируется доплат на сумму
							$plannedProfit = $user->getPlannedProfit($departmentId, $dateStart, $dateEnd);
							
							
							// Принято оплат
							$paymentCount = 0;
							if($userLeads->get()){
								foreach($userLeads->get() as $leadItem){
									$paymentCount += $leadItem->getPaymentCount();
								}
							}
							
							// Суммы фактически полученная
							$profit = $user->getProfit($departmentId, $dateStart, $dateEnd);
                            $percentRevenue = 0;
                            if ( $user->revenue > 0 && $profit > 0 )
                            {
                                $percentRevenue = ceil($profit / $user->revenue * 100);
                            }
							
							//$deadlineTasks = \App\Task::whereUserId($user->id)->where('deadline', '>=', $dateStart)->where('deadline', '<=', $dateEnd)->where('updated_at', '<', DB::raw('`deadline`'))->count();
                            //$completedTask = \App\Task::whereUserId($user->id)->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->where('completed', '=', 'yes')->count();
                            
							$sumProfit += $profit;
                            
                            $sumRevenue += $user->revenue;
                            $sumPlannedProfit += $plannedProfit;
                            //$sumProfitManager += $profitManager;
							
                            //$sumDeadlineTask += $deadlineTasks;
                            //$sumCompletedTask += $completedTask;
							
							$sumAllTasks += $allTasks;
							$sumAppointmentTasks += $appointmentTasks;
							$sumRecallTasks += $recallTasks;
							$sumCancelTasks += $cancelTasks;
							$sumCourtHearingTasks += $courtHearingTasks;
							$sumCompletedAppointmentTasks += $completedAppointmentTasks;
							$sumNewLead += $newLead;
							$sumPaymentCount += $paymentCount;
							
							if (($user->date !== NULL) && (strtotime($user->date) >= strtotime($dateStart)) && (strtotime($user->date) <= strtotime($dateEnd)))
								$last_login = 'Да';
							else
								$last_login = 'Нет';
						}
                        ?>
						@if ($user->blocked !== 1)
                        <tr>
                            <td><a href="{{route('users.view', ['userId' => $user->id])}}"> {{$user->name}}</a></td>
							<td><b>{{$percentRevenue}}%</b> ({{$user->revenue}})</td>
							<td>{{$last_login}}</td>
							<td>{{$allTasks}}</td>
							<td>{{$appointmentTasks}}</td>
							<td>{{$recallTasks}}</td>
							<td>{{$cancelTasks}}</td>
							<td>{{$courtHearingTasks}}</td>
							<td>{{$completedAppointmentTasks}}</td>
							<td>{{$newLead}}</td>
							<td>{{$paymentCount}}</td>
							<td>{{$plannedProfit}}</td>
							<td>{{$profit}}</td>
                        </tr>
						@endif

                    @endforeach
                    </tbody>
					<tfoot>
						<tr style=" font-weight: bold;">
							<td>Итог</td>
							<?php
								$sumPercentRevenue = 0;
								if ( $sumRevenue > 0 && $sumProfit > 0 )
								{
									$sumPercentRevenue = ceil($sumProfit / $sumRevenue * 100);
								}
							?>
							<td>{{$sumPercentRevenue}}% ({{$sumRevenue}})</td>
							<td></td>
							<td>{{$sumAllTasks}}</td>
							<td>{{$sumAppointmentTasks}}</td>
							<td>{{$sumRecallTasks}}</td>
							<td>{{$sumCancelTasks}}</td>
							<td>{{$sumCourtHearingTasks}}</td>
							<td>{{$sumCompletedAppointmentTasks}}</td>
							<td>{{$sumNewLead}}</td>
							<td>{{$sumPaymentCount}}</td>
							<td>{{$sumPlannedProfit}}</td>
							<td>{{$sumProfit}}</td>
						</tr>
					</tfoot>
				</table>
            </div>
        </div>
    </div>
</div>
