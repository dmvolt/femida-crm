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

                                <div class="col-md-5 col-md-offset-1">
                                    <div class="form-group clearfix">
                                        {!! $store->render('name') !!}
                                    </div>
                                    <div class="form-group clearfix">
                                        {!! $store->render('cost') !!}
                                    </div>

                                    <div id="service-check">
                                        <div class="alert alert-danger"> Общая сумма услуги и сумма оплат совпадает</div>
                                    </div>
                                </div>


                                <div class="col-md-5" style="margin-left: 15px;">
                                    <button type="button" class="btn btn-default btn-sm" data-toggle="modal" data-target="#addPaymentModal">Добавить оплату</button>
                                    <ul class="todo-list m-t" id="payments">
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <br />
                        {!! $store->footer !!}
                    </div>

                    <div class="modal inmodal" id="addPaymentModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content animated bounceInRight">
                                <div class="modal-header">
                                    <h4 class="modal-title">Оплата по сделке</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group"><label>Сумма платежа</label> <input type="text" required name="cost" id="cost" placeholder="Сумма платежа" class="form-control"></div>
                                    <div class="form-group"><label>Количество дней</label> <input type="text" required name="days" id="days" placeholder="Кол-во дней" class="form-control"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-white" data-dismiss="modal">Отмена</button>
                                    <button type="button" class="btn btn-primary" id="add-payment">Сохранить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="hidden" id="payment-template">
                        <li>
                            <div class="icheckbox_square-green"></div>
                            <span class="m-l-xs">Сумма:</span><span class="m-l-xs cost">0</span>
                            <span class="m-l-xs">Дней после создания:</span><span class="m-l-xs days">0</span>
                            <a href="#" class="payment-remove"><i class="fa fa-remove pull-right"></i></a>

                            <input type="hidden" class="cost"  name="payments[cost][]">
                            <input type="hidden" class="days"  name="payments[days][]">
                        </li>
                    </div>
                    <script type="text/javascript">
                        checkSum = function ()
                        {
                            var paymentSum = 0;

                            $('#payments').find('input.cost').each(function () {
                                paymentSum = paymentSum + parseInt($(this).val());
                            });

                            var success = parseInt($('input#cost').val()) == paymentSum;
                            if ( success )
                            {
                                $('#service-check').html('<div class="alert alert-success"> Общая сумма услуги и сумма оплат совпадает</div>')
                            }
                            else
                            {
                                $('#service-check').html('<div class="alert alert-danger"> Общая сумма услуги и сумма оплат не совпадает</div>')
                            }
                        };

                        addPayment = function(cost, days)
                        {
                            var template = $('#payment-template').find('li').clone();

                            $(template).find('span.cost').text(cost);
                            $(template).find('span.days').text(days);

                            $(template).find('input.cost').val(cost);
                            $(template).find('input.days').val(days);

                            $('#payments').append(template);

                            $('#addPaymentModal').modal('hide');

                            checkSum();
                        };

                        $('#add-payment').on('click', function ()
                        {
                            var modalData = $('#addPaymentModal');
                            addPayment(modalData.find('#cost').val(), modalData.find('#days').val());
                        });

                        $('#payments').on( 'click', '.payment-remove', function ()
                        {
                            $(this).closest('li').remove();
                            checkSum();
                        });

                        $(function ()
                        {
                            @foreach($store->model->payments as $payment)
                                addPayment({{$payment->cost}}, {{$payment->days}});
                            @endforeach

                            checkSum();
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection