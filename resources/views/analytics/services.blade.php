<div id="tab-4" class="tab-pane">
    <div class="panel-body">
        @include('analytics.filters')

        <div class="panel panel-default">
            <div class="panel-heading">
                Статистика по сделкам
            </div>
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Услуга</th>
                        <th>Прибыль</th>
                        <th>Планируемая прибыль</th>
                        <th>Кол-во оплат</th>
                        <th>Сделок</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\LeadService::all() as $service)
                        <tr>
                            <td>{{$service->name}}</td>
                            <td>{{$service->getProfit($departmentId, $teamId, $dateStart, $dateEnd)}}</td>
                            <td>{{$service->getPlannedProfit($departmentId, $teamId, $dateStart, $dateEnd)}}</td>
                            <td>{{$service->getTaskCount($departmentId, $teamId, $dateStart, $dateEnd)}}</td>
                            <td>{{$service->getLeadsCount($departmentId,  $teamId,$dateStart, $dateEnd)}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
