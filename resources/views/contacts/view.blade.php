@extends('layouts.default')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row animated fadeInRight">
        <div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Информация о клиенте</h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">

                    </div>
                    <div class="ibox-content profile-content">
                        <h4><strong>{{$contact->name}}</strong> <a href="{{route('contacts.store', ['contactId' => $contact->id])}}" class="btn btn-primary btn-sm pull-right"> Редактировать</a></h4>
                        <hr>
                        @if ($contact->address)
                            <p><i class="fa fa-map-marker"></i> {{$contact->address}}</p>
                        @endif

                        <p><i class="fa fa-envelope"></i> <strong>E-mail:</strong> {{$contact->email}}</p>
                        <p><i class="fa fa-phone"></i> <strong>Телефон:</strong> {{$contact->phone}}</p>
                        @if ( $contact->user )
                            <p><i class="fa fa-user"></i> <strong>Менеджер:</strong> <a href="{{route('users.view', ['userId' => $contact->user->id])}}">{{$contact->user->name}}</a> </p>
                        @endif

                        <div class="user-button">
                            <div class="row">
                                <div class="col-md-6 col-md-offset-3">
                                </div>
                            </div>
                        </div>

                        <div class="ibox border-bottom">
                            <button type="button" class="btn btn-primary btn-sm btn-block collapse-link ui-sortable"><i class="fa fa-chevron-down"></i> Подробнее</button>

                            <div class="ibox-content" style="display: none;">
                                <h2>
                                    Паспортные данные
                                </h2>
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
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Сделки</h5>

                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">
                    </div>
                    <div class="ibox-content profile-content">
                        <a href="{{route('leads.store', [null, 'contact_id' => $contact->id])}}" class="btn btn-success btn-xs pull-right">Добавить</a>
                        <br>

                        @forelse($contact->leads as $lead)
                            <a href="{{route('leads.view', ['leadId' => $lead->id])}}">{{$lead->name}}</a> - {{$lead->created_at}}  <hr>
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
                        <a href="{{route('tasks.store', [null, 'contact_id' => $contact->id])}}" class="btn btn-success btn-xs pull-right">Добавить</a>
                        <br>

                        @forelse($contact->tasks as $task)
                            <a href="{{route('tasks.view', ['taskId' => $task->id])}}">{{$task->name}}</a> - {{$task->created_at}}  <hr>
                        @empty
                            <p>Задач не найдено</p>
                        @endforelse
                    </div>
                </div>
            </div>
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
                    @include('contacts.view.activity', ['contact' => $contact])
                </div>
            </div>

        </div>
    </div>
</div>
@endsection