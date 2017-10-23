@extends('layouts.default')

@section('content')
    <style>
        .row .leads-board
        {
            display: inline-block;
            vertical-align: top;
        }

        .leads-list {
            margin-top: 20px;
        }
        .leads-list table tr td {
            height: 46px;
            vertical-align: middle;
            border: none;
        }
        .client-avatar img {
            width: 28px;
            height: 28px;
            border-radius: 50%;
        }
        .client-detail .vertical-timeline-content p {
            margin: 0;
        }
        .leads-list .nav-tabs > li.active > a,
        .leads-list .nav-tabs > li.active > a:hover,
        .leads-list .nav-tabs > li.active > a:focus {
            border-bottom: 1px solid #fff;
        }

    </style>

    <div class="wrapper wrapper-content animated fadeInRight">
		<!--
			Выбор филиала
		-->
		<!--
		<a href="/leads/departments/0" class="btn btn-primary" type="submit">Все филиалы</a>
		<a href="/leads/departments/1" class="btn btn-primary" type="submit">Филиал1</a>
		<a href="/leads/departments/2" class="btn btn-primary" type="submit">Филиал2</a>
		-->
		{!! $mypar !!}
		
		{!! $deps !!}
		<!--
			Фильтры и список сделок для выбранного филиала
		-->
        <div class="leads-list ibox">
            <div class="table-responsive">
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
                {!! $leadsTable !!}
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){

            $(".connectList").sortable({
                connectWith: ".connectList",
                update: function( event, ui ) {
                    var item = ui.item;

                    $.get( "<?=route('leads.statusUpdate')?>",
                            { lead_id: ui.item.attr('data-id'), status_id:  $(ui.item).closest('.leads-board').attr('data-id')},
                            function (response)
                            {

                            }
                    );

                }
            }).disableSelection();
        });
    </script>

    @include('contacts.profileModal')

@endsection
