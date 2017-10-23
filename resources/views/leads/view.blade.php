@extends('layouts.default')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row animated fadeInRight">
        <div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Информация о сделке</h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">

                    </div>
                    <div class="ibox-content profile-content">
                        <h4><strong>{{$lead->name or null}}</strong>
                            @can('update', $lead) <a href="{{route('leads.store', ['leadId' => $lead->id])}}" class="btn btn-primary btn-sm pull-right"> Редактировать</a> @endcan
                        </h4>
                        <hr>
                        <div class="clearfix"></div>

							<p><i class="fa fa-city"></i> <strong>Город:</strong> {{$lead->city or null}}</p>
                        @if( $lead->contact )
                            <p><i class="fa fa-users"></i> <strong>Контакт:</strong> <a href="{{route('contacts.view', ['contactId' => $lead->contact->id])}}">{{$lead->contact->name}}</a> </p>
                            <p><i class="fa fa-phone"></i> <strong>Телефон:</strong> {{$lead->contact->phone or null}}</p>
                        @endif

                        @if( $lead->user )
                            <p><i class="fa fa-user"></i> <strong>Менеджер:</strong> <a href="{{route('users.view', ['userId' => $lead->user->id])}}">{{$lead->user->name}}</a> </p>
                        @endif

                        <p><i class="fa fa-list"></i> <strong>Статус:</strong> {{$lead->status->name}}</p>
                        <p><i class="fa fa-money"></i> <strong>Сумма сделки:</strong> {{$lead->budget}}</p>
                        <p><i class="fa fa-money"></i> <strong>Услуга:</strong> {{$lead->service->name or '-'}}</p>

                        <p><i class="fa fa-calendar"></i> <strong>Дата создания:</strong> {{$lead->created_at}}</p>
                        <p><i class="fa fa-calendar"></i> <strong>Дата обновления:</strong> {{$lead->updated_at}}</p>

                    </div>
                </div>
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Активные задачи</h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">
                    </div>
                    <div class="ibox-content profile-content">
                        <p>
                            <strong>Подтверждено оплат на сумму: {{$lead->getPaymentSum()}}</strong>
                            <a href="{{route('tasks.store', [null, 'lead_id' => $lead->id])}}" class="btn btn-primary btn-xs pull-right">Добавить</a>
                            <br>
                        </p>

                        <table class="table">
                            @forelse($lead->tasks as $task)
                                <tr>
                                    <td>
                                        {!! $task->status !!}
                                    </td>
                                    <td>
                                        <strong><a href="{{route('tasks.view', ['taskId' => $task->id])}}">{{$task->name}}</a> </strong>
                                    </td>
                                    <td>
                                        {{$task->deadline}}
                                    </td>
                                </tr>
                            @empty
                                <strong>Задачи не найдена</strong>
                            @endforelse
                        </table>
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
                        {!! $lead->description or '-' !!}

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
                    @include('leads.view.activity', ['lead' => $lead])
                </div>
            </div>

        </div>
    </div>
</div>
@endsection