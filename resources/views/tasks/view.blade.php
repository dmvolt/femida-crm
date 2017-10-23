@extends('layouts.default')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row animated fadeInRight">
        <div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Информация о задаче</h5> {!! $task->status !!}

                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">

                    </div>
                    <div class="ibox-content profile-content">
                        <h4><strong>{{$task->name}}</strong>
                            <div class="pull-right">
                                @if( ! $task->isCompleted() )
                                    <a href="{{route('tasks.store', ['leadId' => $task->id])}}" class="btn btn-default btn-sm"> Редактировать</a>
                                    <a href="{{route('tasks.completed', ['leadId' => $task->id])}}" class="btn btn-primary btn-sm confirm"> Выполнено</a>
                                @endif
                            </div>
                        </h4>
                        <hr>
                        <div class="clearfix"></div>

                        <p><i class="fa fa-calendar"></i> <strong>Дата выполнения:</strong> {{$task->deadline}}</p>
                        <p><i class="fa fa-calendar"></i> <strong>Дата создания:</strong> {{$task->created_at}}</p>
                        <p><i class="fa fa-calendar"></i> <strong>Дата обновления:</strong> {{$task->updated_at}}</p>

                        <p><i class="fa fa-user"></i> <strong>Назначена:</strong> <a href="{{route('users.view', ['userId' => $task->user->id])}}">{{$task->user->name}}</a></p>
                        <p><i class="fa fa-user"></i> <strong>Автор:</strong> <a href="{{route('users.view', ['userId' => $task->author->id])}}">{{$task->author->name}}</a> </p>
                        <p><i class="fa fa-list"></i> <strong>Тип:</strong> {{$task->typeName}} </p>


                        @if($task->contact)
                            <p><i class="fa fa-user"></i> <strong>Прикрепленный контакт:</strong> <a href="{{route('contacts.view', ['contactId' => $task->contact->id])}}">{{$task->contact->name}}</a></p>
                        @endif

                        @if($task->lead)
                            <p><i class="fa fa-user"></i> <strong>Прикрепленная сделка:</strong> <a href="{{route('leads.view', ['leadId' => $task->lead->id])}}">{{$task->lead->name}}</a></p>
                        @endif

                        @if($task->type == 'approved_payment')
                            <p><i class="fa fa-rub"></i> <strong>Сумма оплаты:</strong> {{$task->cost}} </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Описание</h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">
                    </div>
                    <div class="ibox-content profile-content">
                        {!! $task->description !!}
                    </div>
                </div>
            </div>
{{--
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Напоминания </h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">
                    </div>
                    <div class="ibox-content profile-content">
                        <button class="btn btn-success btn-xs pull-right" data-toggle="modal" data-target="#addNotification">Добавить</button>
                        <div class="clearfix"></div>
                        <ul class="todo-list m-t">
                            @forelse($task->notifications as $notification)
                                <li>
                                    <div class="icheckbox_square-green"></div>
                                    <span class="m-l-xs">{{$notification->text}} </span>
                                    <small class="pull-right label @if($notification->created == 'yes') label-primary @else label-info  @endif"><i class="fa fa-clock-o"></i> {{$notification->datetime}}</small>
                                </li>
                            @empty
                                Напоминаний не создано
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div class="modal inmodal" id="addNotification" tabindex="-1" role="dialog" aria-hidden="true">
                    <form method="post" action="{{route('tasks.addNotification', ['taskId' => $task->id])}}">
                        {!! csrf_field() !!}
                        <div class="modal-dialog">
                            <div class="modal-content animated bounceInRight">
                                <div class="modal-header">
                                    <h4 class="modal-title">Добавить напоминание к задаче</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group"><label>Текст напоминания</label> <input type="text" required name="text" placeholder="Текст напоминания" class="form-control"></div>
                                    <div class="form-group"><label>Дата и время</label> <input type="datetime" required name="datetime" placeholder="Дата и время" class="datetime-input form-control"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Отмена</button>
                                    <button type="submit" class="btn btn-primary">Сохранить</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
--}}
        </div>
        <div class="col-md-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Последние события</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @include('tasks.view.activity', ['task' => $task])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection