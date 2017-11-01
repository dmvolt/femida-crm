<tr data-payment-id="{{$_payment->id}}" data-lead-id="{{$leadId}}" id="payment-{{$_payment->id}}">
    <td class="deadline-td">{{$_payment->deadline}}</td>
	<td class="cost-td">{{$_payment->cost}}</td>
    <td class="cost-td">{{$_payment->income ? $_payment->income->name : ''}}</td>

    @can('update', $_lead)
        @if ( $_payment->isCompleted() )
            <td>
                <span class="" style="color: #1ab394;">Оплачено</span>
            </td>
        @else
            <td>
                <button data-action="{{route('contacts.taskCompleted', ['taskId' => $_payment->id])}}" class="payment-check btn btn-default"><i class="fa fa-check"></i></button>
                <button class="payment-edit btn btn-default"><i class="fa fa-pencil"></i></button>
                <button data-action="{{route('contacts.taskDelete', ['taskId' => $_payment->id])}}" class="payment-remove btn btn-default"><i class="fa fa-trash"></i></button>
            </td>
        @endif
    @else
        <td></td>
    @endcan

</tr>
