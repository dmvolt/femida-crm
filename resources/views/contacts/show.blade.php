<div class="modal-header">
    <h4 class="modal-title">
        <strong>{{$contact->name}}</strong>
        <span class="pull-right">
            @can('update', $contact)
					<a href="{{route('contacts.store', ['contactId' => $contact->id])}}" class="btn btn-primary btn-sm chng"> Редактировать</a>
            @endcan
			@if ($contact->tasks->where('type', '!=', 'make_appointment')->count() > 0 || $contact->leads->count() > 0)
				<a href="#" class="btn btn-default btn-sm" data-dismiss="modal"> Закрыть</a>
			@else
				@if ($contact->user_id !== $user_id)
					<a href="#" class="btn btn-default btn-sm" data-dismiss="modal"> Закрыть</a>
				@endif
			@endif
        </span>
    </h4>
</div>
<div class="modal-body">
    <div class="row animated fadeIn">
        <div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Информация о клиенте</h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">

                    </div>
                    <div class="ibox-content profile-content">
                        @if ($contact->address)
                            <p><i class="fa fa-map-marker"></i> {{$contact->address}}</p>
                        @endif

                        <p><i class="fa fa-envelope"></i> <strong>E-mail:</strong> {{$contact->email}}</p>
                        <p><i class="fa fa-phone"></i> <strong>Телефон:</strong> {{$contact->phone}}</p>
                        @if ( $contact->user )
                            <p><i class="fa fa-user"></i> <strong>Менеджер:</strong> <a href="{{route('users.view', ['userId' => $contact->user->id])}}">{{$contact->user->name}}</a> </p>
							@if ( $department != null )
							<p><i class="fa fa-map-marker"></i> <strong>Город:</strong> 
							{{$department->city}}
							</p>
							@endif							
                        @endif
						
						@if (!empty($important))
							@foreach ($important as $task)
								<p><i class="fa fa-user"></i> <strong>Ответственный:</strong> <a href="{{route('users.view', ['userId' => $task->user->id])}}">{{$task->user->name}}</a> </p>	
							@endforeach
						@endif

                        <div class="user-button">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                </div>
                            </div>
                        </div>

                        <div class="ibox border-bottom">
							<h3>
                                Информация о клиенте
                            </h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <tr>
                                            <td><strong>Дата рождения:</strong></td>
                                            <td>{{$contact->data->contact_birth or null}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Место жительства(факт.):</strong></td>
                                            <td>{{$contact->data->contact_address or null}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>ИНН:</strong></td>
                                            <td>{{$contact->data->contact_inn or null}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Сумма кредита:</strong></td>
                                            <td>{{$contact->data->credit_sum or null}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Цель кредита:</strong></td>
                                            <td>{{$contact->data->credit_target or null}}</td>
                                        </tr>
										
										<tr>
                                            <td><strong>Залог:</strong></td>
                                            <td>
											@if (isset($contact->data) && $contact->data->is_pledge)
												да
											@else 
												нет
											@endif 
											</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Поручитель:</strong></td>
                                            <td>
											@if (isset($contact->data) && $contact->data->is_guarantor)
												да
											@else 
												нет
											@endif 
											</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Справка о доходах:</strong></td>
                                            <td>
											@if (isset($contact->data) && $contact->data->is_reference)
												да
											@else 
												нет
											@endif 
											</td>
                                        </tr>
										<tr>
                                            <td><strong>Открытые просрочки:</strong></td>
                                            <td>
											@if (isset($contact->data) && $contact->data->is_delay)
												да
											@else 
												нет
											@endif 
											</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
							
                            <h3>
                                Паспортные данные
                            </h3>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table">
                                        <tr>
                                            <td><strong>Серия и номер:</strong></td>
                                            <td>{{$contact->data->number or null}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Дата выдачи:</strong></td>
                                            <td>{{$contact->data->date or null}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Выдан:</strong></td>
                                            <td>{{$contact->data->issued or null}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Код:</strong></td>
                                            <td>{{$contact->data->code or null}}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Адрес:</strong></td>
                                            <td>{{$contact->data->address or null}}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @can('update', $contact)
                <div class=" center-text task-buttons">
                    @foreach(App\Task::$activeTypes as $_key => $_name)
                        <button class="btn btn-info" type="button" data-type="{{$_key}}">{{$_name}}</button>
                    @endforeach
                </div>
                <hr>
            @endcan


            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Задачи</h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">

                    </div>
                    <div class="ibox-content">
                        <table id="task-list" class="table">
							@if($contact->tasks)
								@foreach($contact->tasks as $task)
									@include('contacts.task.view', ['task' => $task, 'user_id' => $user_id])
								@endforeach
							@endif
                        </table>

                        <div id="task-form" style="display: none;">

                            <div class="form-group clearfix" id="fg_deadline">
							
								@if(\Auth::user()->isAdmin() && !empty($users))
									<label for="deadline" class="col-sm-2 control-label required">Ответственный</label>
									<select class="form-control form-control user-select" name="user_id">
										@foreach($users as $team_name => $team_users)
											<optgroup label="{{$team_name}}">
												@foreach($team_users as $item)
													<option value="{{$item->id}}">{{$item->name}}</option>
												@endforeach
											</optgroup>
										@endforeach
									</select>
								@endif
								
                                <label for="deadline" class="col-sm-2 control-label required">Дата</label>
                                <input class="form-control form-control datetime-input" type="text" id="deadline" name="deadline">
								<input class="form-control form-control description-input" type="text" id="description" name="description">
                                <input class="input-type" type="hidden" name="type">
                            </div>

                            <a href="{{route('contacts.task', ['contactId' => $contact->id])}}" class="btn btn-primary btn-sm task-create"> Добавить</a>
                            <a href="#" class="btn btn-default btn-sm task-cancel"> Отмена</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Оплаты</h5>

                </div>
                <div>
                    <div class="ibox-content profile-content">
                        <table class="table">
                            @forelse($contact->tasks()->whereType('approved_payment')->get() as $task)
                                <tr>
                                    <td>
                                        @if ( $task->isCompleted() )
                                            <span class="label label-primary"><i class="fa fa-check" aria-hidden="true"></i>   </span>
                                        @endif
                                    </td>
                                    <td>
                                        {{$task->name}} - {{$task->created_at}}  <hr>
                                    </td>

                                    <td>
                                        {{$task->cost}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>
                                        <p>Оплат не найдено</p>
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Информация</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @include('contacts.view.activity', ['contact' => $contact])
                </div>
            </div>

        </div>
    </div>
</div>
<div class="modal-footer">
</div>

<script type="text/javascript">
    $('.remove-task').on('click', function () {

        if (confirm("Вы действительно хотите удалить эту задачу?"))
        {
            var button = $(this);

            $.ajax({
                type: "POST",
                url: button.attr('data-action'),
                data: {_token: window.Laravel.csrfToken},
                success: function (content)
                {
                    button.closest('tr').remove();
                },
            });
        }
    });
</script>
