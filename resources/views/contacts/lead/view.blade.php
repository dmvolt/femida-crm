@can('show', $_lead)
    <div class="panel panel-success lead-panel" data-id="{{$_lead->id}}">
        <div class="panel-heading">
            Договор №{{$_lead->number or null}} - {{$_lead->status->name or null}} {{$_lead->name or null}}
            @can('delete', $_lead)
                <div class="pull-right">
                    Статус: <strong>{{$_lead->status->name or null}}</strong>
                    <button data-action="{{route('leads.delete', ['leadId' => $_lead->id])}}" class="remove-lead btn btn-warning btn-xs"><i class="fa fa-trash-o"></i></button>
                </div>
            @endcan
        </div>

        <div class="panel-body">
            @can('update', $_lead)
                <div class="pull-left">
                    <button class="btn btn-success payment-add-btn" type="button">Добавить оплату</button>
                </div>
            @endcan
            <div class="center-text pull-right lead-change-status" data-id="{{$_lead->id}}">
                @can('update', $_lead)
                    @foreach(\App\LeadStatus::all() as $_statuses)
                        <button data-id="{{$_statuses->id}}" class="btn btn-info @if($_statuses->id == $_lead->status_id) active @endif"  type="button" >{{$_statuses->name}}</button>
                    @endforeach
                @endcan
            </div>

            <div class="clearfix"></div>
            <div class="payment-content">
                <div class="payment-update-container">

                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Сумма</th>
						<th>Тип оплаты</th>
                        <th>Принять</th>
                    </tr>
                    </thead>
                    <tbody class="payment-tr">
                    <? $payments = $_lead->tasks()->whereType('approved_payment')->orderBy('deadline', 'ASC')->get() ?>

                    @forelse($payments as $_payment)
                        @include('contacts.lead.payment', ['$_payment' => $_payment, 'leadId' => $_lead->id])
                    @empty
                        <tr>
                            <td colspan="3"><span>Оплат не найдено</span></td>
                        </tr>
                    @endforelse

                    <table class="table result-payment-table">
                        <thead>
                        <tr>
                            <th>Сумма сделки</th>
                            <th>Сумма оплат</th>
                            <th>Оплачено</th>
                            <th>Не оплачено</th>
                        </tr>
                        </thead>
                        <tr>
                            <td class="budget">{{$_lead->budget}}</td>
                            <td>{{$payments->sum('cost')}}</td>
                            <td>{{$payments->where('completed', '=', 'yes')->sum('cost')}}</td>
                            <td>{{$payments->where('completed', '=', 'no')->sum('cost')}}</td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <span class="alarm-text-payment text-danger @if($_lead->budget == $payments->sum('cost')) hidden @endif">Сумма сделки и сумма оплат не совпадает</span>
                            </td>
                        </tr>
                    </table>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endcan

