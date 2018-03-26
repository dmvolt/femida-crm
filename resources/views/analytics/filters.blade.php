<form role="form" class="form-inline" style="margin-bottom: 15px;">
    <div class="form-group" id="data_5">
		<select class="form-control" name="user_status" id="user_status">
            @foreach(\App\User::$statuses as $_statusAlias => $_statusName)
                <option value="{{$_statusAlias}}" @if($userStatus == $_statusAlias) selected @endif>{{$_statusName}}</option>
            @endforeach
        </select>
		
        <select class="form-control" name="department_id" id="department_id" @if( ! $canChangeDepartment ) disabled @endif>
            <option value="">Все филиалы</option>
            @foreach(\App\Department::all() as $_department)
                <option value="{{$_department->id}}" @if($departmentId == $_department->id) selected @endif>{{$_department->name}}</option>
            @endforeach
        </select>

        <select class="form-control" name="team_id"  id="team_id" @if(! $canChangeTeam) disabled @endif>
            <option value="">Все отделы</option>
            @foreach(\App\Team::all() as $_team)
                <option data-department-id="{{$_team->department_id}}" value="{{$_team->id}}" @if($teamId == $_team->id) selected @endif>{{$_team->name}}</option>
            @endforeach
        </select>

        <div class="input-daterange input-group" id="datepicker">
            <span class="input-group-addon">От</span>
            <input type="text" class="date-input input-sm form-control" name="dateStart" value="{{$dateStart->format('Y-m-d')}}">
            <span class="input-group-addon">До</span>
            <input type="text" class="date-input input-sm form-control" name="dateEnd" value="{{$dateEnd->format('Y-m-d')}}">
        </div>
    </div>

    <button class="btn btn-primary" type="submit">Показать</button>
    <a class="btn btn-default" href="/analytics">Сброс</a>
</form>

<script type="text/javascript">
    var updateFilters = function (depId) {
        if ( depId )
        {
            $('#team_id').find('option').each(function ()
            {
                var opDepId = $(this).attr('data-department-id');

                if ( opDepId )
                {
                    if ( opDepId == depId )
                    {
                        $(this).removeClass('hidden');
                    }
                    else
                    {
                        $(this).addClass('hidden');
                    }
                }
            });
        }
        else
        {
            $('#team_id').find('option').each(function ()
            {
                $(this).removeClass('hidden');
            });
        }
    };

    $('#department_id').on('change', function ()
    {
        var depId = $(this).val();
        updateFilters(depId)

    });

    updateFilters($('#department_id').val())
</script>
