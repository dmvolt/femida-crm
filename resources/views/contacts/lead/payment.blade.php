<tr data-payment-id="{{$_payment->id}}" data-lead-id="{{$leadId}}" id="payment-{{$_payment->id}}">
    <td class="deadline-td">{{$_payment->getDeadlineAttribute($_payment->deadline)}}</td>
	<td class="cost-td">{{$_payment->cost}}</td>
    <td class="income-td">{{$_payment->income ? $_payment->income->name : ''}}</td>

    @can('update', $_lead)
        @if ($_payment->isCompleted())
            <td class="action-completed">
                <span class="" style="color: #1ab394;">Оплачено</span>
				@if (\Auth::user()->isAdmin())
					<button data-action="{{route('contacts.taskCanceled', ['taskId' => $_payment->id])}}" title="Отменить оплату" class="payment-cancel btn btn-default"><i class="fa fa-ban"></i></button>
				@endif
            </td>
			<td class="action-active" style="display:none;">
				<button data-task="{{$_payment->id}}" class="btn btn-default" id="payment-check-{{$_payment->id}}" title="Подтвердить оплату"><i class="fa fa-check"></i></button>
				<button class="payment-edit btn btn-default"><i class="fa fa-pencil"></i></button>
                <button data-action="{{route('contacts.taskDelete', ['taskId' => $_payment->id])}}" class="payment-remove btn btn-default" title="Удалить оплату"><i class="fa fa-trash"></i></button>
            </td>
        @else
			<td class="action-completed" style="display:none;">
                <span class="" style="color: #1ab394;">Оплачено</span>
				@if (\Auth::user()->isAdmin())
					<button data-action="{{route('contacts.taskCanceled', ['taskId' => $_payment->id])}}" title="Отменить оплату" class="payment-cancel btn btn-default"><i class="fa fa-ban"></i></button>
				@endif
            </td>
            <td class="action-active">
				<button data-task="{{$_payment->id}}" class="btn btn-default" id="payment-check-{{$_payment->id}}" title="Подтвердить оплату"><i class="fa fa-check"></i></button>
				<button class="payment-edit btn btn-default"><i class="fa fa-pencil"></i></button>
                <button data-action="{{route('contacts.taskDelete', ['taskId' => $_payment->id])}}" class="payment-remove btn btn-default" title="Удалить оплату"><i class="fa fa-trash"></i></button>
            </td>
        @endif
    @else
        <td></td>
    @endcan
</tr>
<tr id="payment-complete-template-{{$_payment->id}}" style="display: none;">
	<td colspan="5">
        <div class="panel panel-success payment-complete-item" style="margin-top: 15px;">
            <div class="panel-heading">Подтверждение оплаты</div>
            <div class="panel-body">
                <form class="form-inline" id="payment-complete-form-{{$_payment->id}}" action="{{route('contacts.taskCompleted')}}">
                    {{csrf_field()}}
                    <input type="hidden" id="payment_complete_task_id_{{$_payment->id}}" name="task_id" value="{{$_payment->id}}">
					<div class="form-group" style="">
                        <label class="control-label">Выберите тип оплаты</label>
                        <select class="form-control form-control" id="income_id_{{$_payment->id}}" name="income_id">
							@foreach(\App\Income::all() as $_income)
								<option value="{{$_income->id}}">{{$_income->name}}</option>
							@endforeach
						</select>
                    </div>

                    <div class="form-group" style="">
                        <button class="btn btn-primary m" id="button-payment-complete-save-{{$_payment->id}}"> применить</button>
                        <button class="btn btn-default m button-payment-complete-cancel" id="button-payment-complete-cancel-{{$_payment->id}}"> отмена</button>
                    </div>
                </form>
            </div>
        </div>
	</td>
</tr>
<script type="text/javascript">
	$('#payment-check-{{$_payment->id}}').on('click', function () {
		
		var button = $(this);
		$('#payment-complete-template-{{$_payment->id}}').show();
        $('#payment_complete_task_id_{{$_payment->id}}').val(button.attr('data-task'));
    });
	
	$('#button-payment-complete-save-{{$_payment->id}}').on('click', function () {
		
		var data = $('#payment-complete-form-{{$_payment->id}}').serializeArray().reduce(function(obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});
		
		$.ajax({
			type: "POST",
			url: $('#payment-complete-form-{{$_payment->id}}').attr('action'),
			data: data,
			success: function (content)
			{
				var currentTaskId = $('#payment_complete_task_id_{{$_payment->id}}').val();
				var currentTaskIncome = $('select#income_id_{{$_payment->id}} :selected').text();
				
				var currentPaymentTr = lead_list.find('tr#payment-' + currentTaskId);
				
				currentPaymentTr.find('td.income-td').text(currentTaskIncome);
				currentPaymentTr.find('td.deadline-td').text(content.deadline);
				
				//console.log(content);
				
				currentPaymentTr.find('td.action-completed').show();
				currentPaymentTr.find('td.action-active').hide();
				
				/* currentPaymentTr.find('button').each(function () {
					$(this).attr('disabled', 'disabled');
				}); */
			}
		});
		
		$('#payment-complete-template-{{$_payment->id}}').hide();
        return false;
    });
	
	$('#button-payment-complete-cancel-{{$_payment->id}}').on('click', function(){
        $('#payment-complete-template-{{$_payment->id}}').hide();
        return false;
    });
</script>
