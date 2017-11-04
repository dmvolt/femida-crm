<div id="tab-1" class="tab-pane active">
    <div class="panel-body">
        @include('analytics.filters')

        <div class="panel panel-default">
            <div class="panel-heading">
                Статистика по сделкам
            </div>
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <table class="table table-hover table-bordered">
                    <thead>
                    <? $statuses = \App\LeadStatus::all() ?>
                    <tr>
                        <th>Тип</th>
                        @foreach($statuses as $_status)
                            <th>{{$_status->name}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Созданные сделки</td>
                        @foreach($statuses as $_status)
                            <td>{{$_status->getLeadsCount($departmentId, $teamId, $dateStart, $dateEnd)}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Планируемая прибыль</td>
                        @foreach($statuses as $_status)
                            <td>{{$_status->getPlannedProfit($departmentId, $teamId, $dateStart, $dateEnd)}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Полученая прибыль</td>
                        @foreach($statuses as $_status)
                            <td>{{$_status->getProfit($departmentId, $teamId, $dateStart, $dateEnd)}}</td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Статистика расходов и доходов (общая)
            </div>
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <div id="morris-bar-chart-all"></div>
            </div>
        </div>
		
		<div class="panel panel-default">
            <div class="panel-heading">
                Статистика расходов
            </div>
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <div id="morris-bar-chart-cost"></div>
            </div>
        </div>
		
		<div class="panel panel-default">
            <div class="panel-heading">
                Статистика доходов
            </div>
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <div id="morris-bar-chart-income"></div>

                <script type="text/javascript">
                    $(document).ready(function () {
						
						Morris.Bar({
                            element: 'morris-bar-chart-all',
                            data: {!! $generalChartAll !!},
                            xkey: 'y',
                            ykeys: {!! $chartKeysAll !!},
                            labels: {!! $chartLabelsAll !!},
                            hideHover: 'auto',
                            resize: true,
                            barColors: {!! $chartColorsAll !!}
                        });
						
						Morris.Bar({
                            element: 'morris-bar-chart-cost',
                            data: {!! $generalChartCost !!},
                            xkey: 'y',
                            ykeys: {!! $chartKeysCost !!},
                            labels: {!! $chartLabelsCost !!},
                            hideHover: 'auto',
                            resize: true,
                            barColors: {!! $chartColorsCost !!}
                        });
						
                        Morris.Bar({
                            element: 'morris-bar-chart-income',
                            data: {!! $generalChartIncome !!},
                            xkey: 'y',
                            ykeys: {!! $chartKeysIncome !!},
                            labels: {!! $chartLabelsIncome !!},
                            hideHover: 'auto',
                            resize: true,
                            barColors: {!! $chartColorsIncome !!}
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</div>
