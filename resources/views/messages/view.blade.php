@extends('layouts.default')

@section('content')
<div class="wrapper wrapper-content">
    <div class="row animated fadeInRight">
        <div class="col-md-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Рассылка контактам №{{$message->id}}</h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">

                    </div>
                    <div class="ibox-content profile-content">
                        <h4>
                            <strong>{{$message->name}}</strong>
                            @if ( ! $message->isCompleted() )
                                <div class="pull-right">
                                    <a href="{{route('messages.store', ['messageId' => $message->id])}}" class="btn btn-primary btn-sm"> Редактировать</a>
                                    <a href="{{route('messages.send', ['messageId' => $message->id])}}" class="btn btn-success btn-sm"> Отправить</a>
                                </div>
                            @endif
                        </h4>
                            <div class="clearfix"></div>
                        <hr>
                        <p><i class="fa fa-send"></i> <strong>Статус</strong> {{$message->statusName}}</p>
                        <p><i class="fa fa-envelope"></i> <strong>Тип рассылки</strong> {{$message->type}}</p>
                        <p><i class="fa fa-calendar"></i> <strong>Обновлено</strong> {{$message->updated_at}}</p>

                    </div>
                </div>
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Текст рассылки</h5>
                </div>
                <div>
                    <div class="ibox-content no-padding border-left-right">
                    </div>
                    <div class="ibox-content profile-content">
                        {!! $message->text !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Контакты ({{$message->contacts->count()}})</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    @if ( ! $message->isCompleted() )
                        <div class="pull-right">
                            <a href="{{route('messages.contacts', [null, 'messageId' => $message->id])}}" class="btn btn-primary btn-xs">Добавить</a>
                            <a href="{{route('messages.contactClean', [null, 'messageId' => $message->id])}}" class="btn btn-default btn-xs confirm">Очистить</a>
                        </div>
                    @endif
                    <br>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ФИО</th>
                            <th>Телефон</th>
                            <th>E-mail</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($message->contacts as $contactMess)
                            <?php $contact = $contactMess->contact ?>
                            <tr>
                                <td>
                                    <a href="{{route('contacts.view', ['contactId'=> $contact->id])}}"><span><i class="fa fa-user"></i> {{$contact->name}}</span></a> <br><br>
                                </td>
                                <td>
                                    {{$contact->phone}}
                                </td>
                                <td>
                                    {{$contact->email}}
                                </td>
                                <td>
                                    <a href="{{route('messages.contactDelete', ['contactId'=> $contactMess->id, 'messageId' => $message->id])}}"><i class="glyphicon glyphicon-trash"></i> </a>
                                </td>
                            </tr>
                        @empty
                            <p>Контактов не найдено</p>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection