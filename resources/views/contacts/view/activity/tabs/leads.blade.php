<div id="tab-3" class="tab-pane active">

    @can('update', $contact)
        <div class="pull-right" style="padding-top: 15px;">
            <button class="btn btn-primary" type="button" id="button-add-lead">Новая сделка</button>
        </div>
        <div class="clearfix"></div>
        <hr>
    @endcan

    <div id="payment-update-template" style="display: none;">
        <div class="panel panel-success payment-update-item" style="margin-top: 15px;">
            <div class="panel-heading">Оплаты</div>
            <div class="panel-body">
                <form class="form-inline" id="payment-form-update" action="{{route('contacts.updatePayment')}}">
                    {{csrf_field()}}
                    <input type="hidden" class="payment-id-input" name="payment_id" value="">
                    <input type="hidden" class="lead-id-input" name="lead_id" value="">
                    <div class="form-group" style="">
                        <label for="deadline" class="control-label required">Дата</label>
                        <input class="form-control payment-date-input form-control datetime-input" type="text" name="payment-date">
                    </div>

                    <div class="form-group" style="">
                        <label class=" control-label">Сумма</label>
                        <input class="form-control payment-price-input form-control" type="text" id="price" name="price" value="">
                    </div>

                    <div class="form-group" style="">
                        <button class="btn btn-primary m button-save-payment"> сохранить</button>
                        <button class="btn btn-default m button-cancel-payment"> отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="lead-add" style="display: none;">
        <div class="panel panel-success">
            <div class="panel-heading">
                Новая сделка
            </div>
            <form id="lead-form" action="{{route('contacts.addLead', ['contactId' => $contact->id])}}">
                {{csrf_field()}}
                <div class="panel-body">
                    <div class=" center-text task-buttons">
                        @foreach(\App\LeadStatus::all() as $_status)
                            <label class="bt btn-primar">
                                <input type="radio" name="status" id="{{$_status->id}}" value="{{$_status->id}}" autocomplete="off"> {{$_status->name}}
                            </label>
                        @endforeach
                        <div class="form-group" style="padding-top: 15px;">
                            <label class="col-sm-2 control-label">Услуга</label>
                            <select class="chosen-select form-control" type="select" id="service_id" name="service_id">
                                @foreach(\App\LeadService::all() as $_service)
                                    <option value="{{$_service->id}}">{{$_service->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group" style="padding-top: 15px;">
                            <label class="col-sm-2 control-label">Название</label>
                            <input class="form-control form-control" type="text" id="name" name="name" value="">
                        </div>

                        <div class="form-group" style="padding-top: 15px;">
                            <label class="col-sm-2 control-label">№ договора</label>
                            <input class="form-control form-control" type="text" id="number" name="number" value="">
                        </div>
                    </div>
                    <div class="pull-right">
                        <button role="button" class="btn btn-default m" id="button-cancel-lead"> отмена</button>
                        <button role="button" class="btn btn-primary m" id="button-save-lead"> сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="lead-list">
        @each('contacts.lead.view', $contact->leads, '_lead')
    </div>
</div>

<script type="text/javascript">
    $('#button-add-lead').on('click', function(){
        $('#lead-add').show();
    });

    $('#button-save-lead').on('click', function() {
        var data = $('#lead-form').serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

        $.ajax({
            type: "POST",
            url: $('#lead-form').attr('action'),
            data: data,
            success: function (content)
            {
                $('.lead-list').append(content);
            },
        });

        $('#lead-add').hide();
        return false;
    });

    $('#button-cancel-lead').on('click', function(){
        $('#lead-add').hide();
        return false;
    });

    var lead_list = $('.lead-list');

    lead_list.on('click', '.lead-change-status button', function () {

        $(this).parent().find('button').each(function () {
            $(this).removeClass('active');
        });

        var status_id = $(this).attr('data-id');
        var lead_id = $(this).parent().attr('data-id')
        $(this).addClass('active');

        $.ajax({
            type: "GET",
            url: '{{route('leads.statusUpdate')}}',
            data: {status_id: status_id, lead_id : lead_id},
            success: function (content)
            {
            },
        });
    });

    lead_list.on('click', '.payment-check', function () {

        var button = $(this);

        $.ajax({
            type: "POST",
            url: button.attr('data-action'),
            data: {_token: window.Laravel.csrfToken},
            success: function (content)
            {
                button.parent().find('button').each(function () {
                    $(this).attr('disabled', 'disabled');
                });
            },
        });
    });

    lead_list.on('click', '.payment-remove', function () {

        var button = $(this);

        $.ajax({
            type: "POST",
            url: button.attr('data-action'),
            data: {_token: window.Laravel.csrfToken},
            success: function (content)
            {
               button.parent().find('button').each(function () {
                    button.closest('tr').remove();
                });
            },
        });
    });

    lead_list.on('click', '.payment-edit,.payment-add-btn', function () {
        var button = $(this);

        var payment = $('#payment-update-template').html();
        payment = $(payment);

        var tr = button.closest('tr');
        if (button.hasClass('payment-edit'))
        {
            var cost = tr.find('.cost-td').text();
            var deadline = tr.find('.deadline-td').text();


            payment.find('.payment-id-input').val(tr.attr('data-payment-id'));
            payment.find('.lead-id-input').val(tr.attr('data-lead-id'));
            payment.find('.payment-date-input').val(deadline);
            payment.find('.payment-price-input').val(cost);
        }
        else
        {
            var leadId = button.closest('.lead-panel').attr('data-id');
            payment.find('.lead-id-input').val(leadId);
        }

        payment.find('.datetime-input').datetimepicker({
            format: 'dd-mm-yyyy hh:mm',
            language: 'ru',
            todayBtn: 'linked',
            autoclose: true,
            orientation: "auto"
        });
        button.closest('.panel-body').find('.payment-update-container').html(payment);
    });

    lead_list.on('click', '.button-cancel-payment', function () {
        $(this).closest('.payment-update-item').remove();
        return false;
    });

    lead_list.on('click', '.button-save-payment', function () {
        var form = $(this).closest('form');
        var url = form.attr('action');

        var data = form.serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

        $.ajax({
            type: "POST",
            url: url,
            data: data,
            success: function (content)
            {
                if (data.payment_id)
                {
                    $('tr[data-payment-id='+data.payment_id+']').remove();
                }

                $('.lead-panel[data-id='+data.lead_id+']').find('.payment-tr').html(content);
            },
        });

        $(this).closest('.payment-update-item').remove();
        return false;
    });

    var checkSum = function (tr)
    {
        var paymentSum = 0;
        var leadSum = parseInt(tr.closest('.payment-content').find('.result-payment-table td.budget').text());


        tr.closest('.payment-tr').find('tr').each(function () {
            paymentSum = paymentSum + parseInt($(this).find('.cost-td').text());
        });

        if (leadSum != paymentSum)
        {
            tr.closest('.payment-content').find('.result-payment-table .alarm-text-payment').removeClass('hidden');

        }
        else
        {
            tr.closest('.payment-content').find('.result-payment-table .alarm-text-payment').addClass('hidden');
        }
    };

    $('.payment-tr').on('DOMSubtreeModified', function () {
        checkSum($(this));
    });

    $('.remove-lead').on('click', function () {

        if (confirm("Вы действительно хотите удалить эту задачу?"))
        {
            var button = $(this);

            $.ajax({
                type: "POST",
                url: button.attr('data-action'),
                data: {_token: window.Laravel.csrfToken},
                success: function (content)
                {
                    button.closest('.lead-panel').hide("slow", function(){ $(this).remove(); })
                },
            });
        }
    });

</script>


