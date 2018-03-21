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
                        </div>
                    </div>
                    <div class="ibox-content">
                        {!! $store->header !!}

						{!! $store->message !!}

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									{!! $store->render('type') !!}
								</div>
								<div class="form-group">
									{!! $store->render('title') !!}
								</div>
							</div>
							<div class="col-md-6">
								{!! $store->render('text') !!}
								<p><b>В тексте сообщения можно использовать переменные:</b></p>
								<?php foreach($variables as $key => $value):?>
								{!! $key !!} - {!! $value !!}<br>
								<?php endforeach;?>
							</div>
						</div> 

						{!! $store->footer !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection