@extends('layouts.default')

@section('content')

    <div class="tabs-container">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">Пользователи</a></li>
            @if(Auth::user()->isAdmin())
                <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">Услуги</a></li>
                <li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="true">Филиалы</a></li>
                <li class=""><a data-toggle="tab" href="#tab-5" aria-expanded="true">Отделы</a></li>
                <li class=""><a data-toggle="tab" href="#tab-4" aria-expanded="true">Статусы</a></li>
                <li class=""><a data-toggle="tab" href="#tab-6" aria-expanded="true">Источники</a></li>
				<li class=""><a data-toggle="tab" href="#tab-7" aria-expanded="true">Расходы</a></li>
				<li class=""><a data-toggle="tab" href="#tab-8" aria-expanded="true">Доходы</a></li>
				<li class=""><a data-toggle="tab" href="#tab-9" aria-expanded="true">Оповещения</a></li>
            @endif

        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    {!! $users !!}
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    {!! $services !!}
                </div>
            </div>
            <div id="tab-3" class="tab-pane">
                <div class="panel-body">
                    {!! $departments !!}
                </div>
            </div>
            <div id="tab-5" class="tab-pane">
                <div class="panel-body">
                    {!! $teams !!}
                </div>
            </div>

            <div id="tab-4" class="tab-pane">
                <div class="panel-body">
                    {!! $statuses !!}
                </div>
            </div>
            <div id="tab-6" class="tab-pane">
                <div class="panel-body">
                    {!! $origins !!}
                </div>
            </div>
			<div id="tab-7" class="tab-pane">
                <div class="panel-body">
                    {!! $costs !!}
                </div>
            </div>
			<div id="tab-8" class="tab-pane">
                <div class="panel-body">
                    {!! $incomes !!}
                </div>
            </div>
			<div id="tab-9" class="tab-pane">
                <div class="panel-body">
                    {!! $notifications !!}
                </div>
            </div>
        </div>


    </div>
@endsection
