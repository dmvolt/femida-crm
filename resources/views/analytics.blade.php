@extends('layouts.default')

@section('content')
    <div class="tabs-container">
        <div class="tabs">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">Финансы</a></li>
                <!--<li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">Активность</a></li>-->
                <li class=""><a data-toggle="tab" href="#tab-3" aria-expanded="false">Сотрудники</a></li>
                <li class=""><a data-toggle="tab" href="#tab-4" aria-expanded="false">Услуги</a></li>
                <li class=""><a data-toggle="tab" href="#tab-5" aria-expanded="false">Клиенты</a></li>
            </ul>
            <div class="tab-content">
                @include('analytics.general')
                <!--@include('analytics.activities')-->
                @include('analytics.users')
                @include('analytics.services')
                @include('analytics.contacts')
            </div>
        </div>
    </div>
@endsection
