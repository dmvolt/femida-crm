@extends('layouts.default')

@section('content')
    <div class="row">
        <div class="col-lg-12">

            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Новые заявки ({{\App\Task::getNewRequestCount()}})</h5>

                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <span class="text-danger">{{Session::get('request_failed')}} </span>
                    {!! $tableRequest !!}
                </div>
            </div>

            <div class="ibox">
                <div class="ibox-title">
                    <h5>Список задач</h5>
                </div>
                <div class="ibox-content">
                    <div class="ibox">
					{!! $fasttypes !!}
                        <div class="pull-right">
                            <button type="button" class="btn btn-success collapse-link"><i class="fa  @if($filter->action == 'idle') fa-chevron-down  @else fa-chevron-up @endif"></i> Фильтры</button>
                        </div>
                        <div class="ibox-content" @if($filter->action == 'idle') style="display: none;" @endif>
                            <h2>
                                Фильтры
                            </h2>
                            <div class="row">
                                <div class="col-md-12">
                                    {!! $filter !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! $tasks !!}
                </div>
            </div>
        </div>
    </div>

    @include('contacts.profileModal')

@endsection
