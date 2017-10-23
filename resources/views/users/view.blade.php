@extends('layouts.default')

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row animated fadeInRight">
            <div class="col-md-4">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Информация</h5>
                    </div>
                    <div>
                        <div class="ibox-content no-padding border-left-right">

                        </div>
                        <div class="ibox-content profile-content">
                            <h4><strong>{{$user->name}}</strong></h4>
                            <hr>
                            <p><i class="fa fa-envelope"></i> <strong>E-mail:</strong> {{$user->email}}</p>
                            <p><i class="fa fa-phone"></i> <strong>Телефон:</strong> {{$user->phone}}</p>
                            <p><i class="fa fa-bars"></i> <strong>Филиал:</strong> {{$user->department->name or null}}</p>
                            <p><i class="fa fa-bars"></i> <strong>Отдел:</strong> {{$user->team->name or null}}</p>

                            <hr>
                            <p><i class="fa fa-table"></i> <strong>Серия и номер:</strong> {{$user->serial}}</p>
                            <p><i class="fa fa-table"></i> <strong>Код:</strong> {{$user->code}}</p>
                            <p><i class="fa fa-table"></i> <strong>Выдан:</strong> {{$user->issued}}</p>
                            <p><i class="fa fa-table"></i> <strong>Адрес:</strong> {{$user->address}}</p>
                            <p><i class="fa fa-table"></i> <strong>Дата:</strong> {{$user->date}}</p>

                            <div class="user-button">
                                <div class="row">
                                    <div class="col-md-6 col-md-offset-3">
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Сделки</h5>
                    </div>
                    <div>
                        <div class="ibox-content no-padding border-left-right">
                        </div>
                        <div class="ibox-content profile-content">
                            @forelse($user->leads as $lead)
                                <a href="{{route('leads.view', ['leadId' => $lead->id])}}">{{$lead->name}}</a> - {{$lead->created_at}} <hr>
                            @empty
                                <p>Сделок не найдено</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Задачи</h5>
                    </div>
                    <div>
                        <div class="ibox-content no-padding border-left-right">

                        </div>
                        <div class="ibox-content">
                            @forelse($user->tasks as $task)
                                <a href="{{route('tasks.view', ['taskId' => $task->id])}}">{{$task->name}}</a> - {{$task->created_at}} <hr>
                            @empty
                                <p>Задач не найдено</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection