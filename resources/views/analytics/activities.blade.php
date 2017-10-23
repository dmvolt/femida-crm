<div id="tab-2" class="tab-pane">
    <div class="panel-body">
        @include('analytics.filters')

        <div class="panel panel-default">
            <div class="panel-heading">
                Активность задач
            </div>
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Тип</th>
                        @foreach(\App\Task::$types as $_id => $_name)
                            <th>{{$_name}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Создано</td>
                        @foreach(\App\Task::$types as $_id => $_name)
                            <td>{{\App\Task::whereType($_id)->filterDepartment($departmentId)->filterTeam($teamId)->where('created_at', '>=', $dateStart)->where('created_at', '<=', $dateEnd)->count()}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Выполнено</td>
                        @foreach(\App\Task::$types as $_id => $_name)
                            <td>{{\App\Task::whereType($_id)->filterDepartment($departmentId)->filterTeam($teamId)->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->where('completed', '=', 'yes')->count()}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Просрочено</td>
                        @foreach(\App\Task::$types as $_id => $_name)
                            <td>{{\App\Task::whereType($_id)->filterDepartment($departmentId)->filterTeam($teamId)->where('deadline', '>=', $dateStart)->where('deadline', '<=', $dateEnd)->where('updated_at', '<', DB::raw('`deadline`'))->count()}}</td>
                        @endforeach
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Активность сделок
            </div>
            <?php $statuses = \App\LeadStatus::all() ?>
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Тип</th>
                        @foreach($statuses as $_status)
                            <th>{{$_status->name}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Создано</td>
                        @foreach($statuses as $_status)
                            <td>{{\App\Lead::whereStatusId($_status->id)->filterDepartment($departmentId)->filterTeam($teamId)->where('created_at', '>=', $dateStart)->where('created_at', '<=', $dateEnd)->count()}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Обновлено</td>
                        @foreach($statuses as $_status)
                            <td>{{\App\Lead::whereStatusId($_status->id)->filterDepartment($departmentId)->filterTeam($teamId)->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->count()}}</td>
                        @endforeach
                    </tr>
                    <tr>
                        <td>Закрыто</td>
                        <td colspan="{{$statuses->count()}}">{{\App\Lead::closedStatuses()->filterDepartment($departmentId)->filterTeam($teamId)->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->count()}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Активность контактов
            </div>
            <div class="panel-body" style="width: 100%; margin-left: 0;">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>Создано</th>
                        <th>Обновлено</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{\App\Contact::filterDepartment($departmentId)->filterTeam($teamId)->where('created_at', '>=', $dateStart)->where('created_at', '<=', $dateEnd)->count()}}</td>
                        <td>{{\App\Contact::filterDepartment($departmentId)->filterTeam($teamId)->where('updated_at', '>=', $dateStart)->where('updated_at', '<=', $dateEnd)->count()}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
