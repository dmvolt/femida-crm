<div id="tab-5" class="tab-pane">
    <div class="panel-body">
        @include('analytics.filters')

        {{--<div class="panel panel-default">--}}
            {{--<div class="panel-heading">--}}
                {{--Контакты--}}
            {{--</div>--}}
            {{--<div class="panel-body" style="width: 100%; margin-left: 0;">--}}
                {{--<table class="table table-hover">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th>Контакты</th>--}}
                        {{--<th>Количество</th>--}}
                        {{--<th>Доход</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tbody>--}}
                    {{--<tr>--}}
                        {{--<td>Новые</td>--}}
                        {{--<td>1</td>--}}
                        {{--<td>1</td>--}}
                    {{--</tr>--}}
                    {{--<tr>--}}
                        {{--<td>Повторные</td>--}}
                        {{--<td>1</td>--}}
                        {{--<td>1</td>--}}
                    {{--</tr>--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="panel panel-default">
            <div class="panel-heading">
                Источники
            </div>
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Источник</th>
                        <th>Контактов</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\ContactOrigin::all() as $_origin)
                        <tr>
                            <td>{{$_origin->name}}</td>
                            <td>{{$_origin->users()->where('created_at', '>=', $dateStart)->filterDepartment($departmentId)->filterTeam($teamId)->where('created_at', '<=', $dateEnd)->count()}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{--<div class="panel panel-default">--}}
            {{--<div class="panel-heading">--}}
                {{--Доход--}}
            {{--</div>--}}
            {{--<div class="panel-body" style="width: 100%; margin-left: 0;">--}}
                {{--<table class="table table-hover">--}}
                    {{--<thead>--}}
                    {{--<tr>--}}
                        {{--<th>Клиент</th>--}}
                        {{--<th>Прибыль</th>--}}
                        {{--<th>Планируемая прибыль</th>--}}
                        {{--<th>Суммарная прибыль</th>--}}
                        {{--<th>Сделок</th>--}}
                    {{--</tr>--}}
                    {{--</thead>--}}
                    {{--<tbody>--}}
                    {{--@foreach(\App\Contact::all() as $_contact)--}}
                        {{--<tr>--}}
                            {{--<td>{{$_contact->name}}</td>--}}
                            {{--<td>1</td>--}}
                            {{--<td>1</td>--}}
                            {{--<td>1</td>--}}
                            {{--<td>1</td>--}}
                        {{--</tr>--}}
                    {{--@endforeach--}}
                    {{--</tbody>--}}
                {{--</table>--}}
            {{--</div>--}}
        {{--</div>--}}


    </div>
</div>
