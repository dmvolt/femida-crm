@extends('layouts.default')

@section('content')
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>{{$title or 'Укажите заголовок'}}</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>
                            <a class="close-link">
                                <i class="fa fa-times"></i>
                            </a>
                        </div>
                    </div>
                    <div class="ibox-content">
                        {!! $store->header !!}

                        {!! $store->message !!}

                        @if(!$store->message)
                            <div class="row">

                                <div class="col-md-5 col-md-offset-1" style="">
                                    <div class="form-group clearfix">
                                        {!! $store->render('name') !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        {!! $store->render('email') !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        {!! $store->render('phone', ['class' => 'phone']) !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        {!! $store->render('origin_id') !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        {!! $store->render('user_id') !!}
                                    </div>
                                </div>

                                <div class="col-md-5" style="margin-left: 15px;">
                                    <div class="form-group clearfix">
                                        {!! $store->render('data.number', ['class' => 'passport-num']) !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        {!! $store->render('data.code', ['class' => 'passport-code']) !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        {!! $store->render('data.issued') !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        {!! $store->render('data.address') !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        {!! $store->render('data.date') !!}
                                    </div>
                                </div>
                            </div>
							
							<h3>Информация о клиенте</h3>
							<hr>
							<div class="row">
                                <div class="col-md-5 col-md-offset-1" style="">
                                    <div class="form-group clearfix">
                                        {!! $store->render('data.contact_birth') !!}
                                    </div>
									<div class="form-group clearfix">
                                        {!! $store->render('data.contact_address') !!}
                                    </div>
									<div class="form-group clearfix">
                                        {!! $store->render('data.contact_inn', ['class' => 'inn']) !!}
                                    </div>
                                </div>

                                <div class="col-md-5" style="margin-left: 15px;">
									<div class="form-group clearfix">
                                        {!! $store->render('data.credit_sum', ['class' => 'sum']) !!}
                                    </div>
									<div class="form-group clearfix">
                                        {!! $store->render('data.credit_target') !!}
                                    </div>
									<hr>
									<div class="form-check clearfix">
                                        {!! $store->render('data.is_pledge', ['class' => 'checkbox']) !!}
                                    </div>
									<div class="form-check clearfix">
										{!! $store->render('data.is_guarantor', ['class' => 'checkbox']) !!}
                                    </div>
									<div class="form-check clearfix">
										{!! $store->render('data.is_reference', ['class' => 'checkbox']) !!}
                                    </div>
									<div class="form-check clearfix">
										{!! $store->render('data.is_delay', ['class' => 'checkbox']) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        <br />
                        {!! $store->footer !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection