<?php
use Carbon\Carbon;
$departmentId = null; 
//$dateStart = Carbon::now()->subDays(30);
//$dateStart = new Carbon('first day of this month');
$dateStart = Carbon::now()->startOfMonth();
$dateEnd = Carbon::now()->addHours(23)->addMinute(59);

// количество сделок
$leads = \App\Lead::whereUserId(Auth::user()->id)->where('created_at', '>=', $dateStart)->where('created_at', '<=', $dateEnd);
?>
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu1">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="{{Auth::user()->getUrlAvatar()}}" />
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{Auth::user()->name}}</strong>
                             </span> <span class="text-muted text-xs block">{{Auth::user()->getPost()}} <b class="caret"></b></span> </span> </a>
						<p class="text-muted">План: {{Auth::user()->getProfit($departmentId, $dateStart, $dateEnd)}} из {{Auth::user()->revenue}}<br>
							Сделок: {{$leads->count()}}
						</p>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{route('users.view')}}">Профиль</a></li>
                        <li class="divider"></li>
                        <li><a href="/logout">Выход</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    <img src="/logo_mini.png" alt="Меч Фемиды">
                </div>
            </li>
		</ul>	
		<div id="side-menu2">
			<ul class="nav">
				@foreach(Config::get('crm.menu', []) as $_key => $_item)
					@can('showCategory', $_item['baseModel'])
						<li @if( $currentMenuId == $_key) class="active" @endif>
							<a href="{{$_item['url']}}">
								<i class="{{$_item['icons']}}"></i> <span class="nav-label">{{$_item['name']}}</span>
								@if ($_key == 'tasks')
									@if(\App\Task::getNewRequestCount() > 0 )
											<span class="label label-success">{{\App\Task::getNewRequestCount()}}</span>
									@endif
								@endif
							</a>
						</li>
					@endcan
				@endforeach
			</ul>
			<h4 class="left-title">Сервисы</h4>
			<ul class="nav">
				@foreach(Config::get('crm.menu3', []) as $_item)
					@can($_item['police'], $_item['baseModel'])
						<li>
							<a href="{{$_item['url']}}" target="service-iframe">
								<i class="{{$_item['icons']}}"></i> <span class="nav-label">{{$_item['name']}}</span>
							</a>
						</li>
					@endcan
				@endforeach
			</ul>
			<h4 class="left-title">Обучение</h4>
			<ul class="nav">
				@foreach(Config::get('crm.menu2', []) as $_item)
					@can($_item['police'], $_item['baseModel'])
						<li>
							<a href="{{$_item['url']}}" target="_blank">
								<i class="{{$_item['icons']}}"></i> <span class="nav-label">{{$_item['name']}}</span>
							</a>
						</li>
					@endcan
				@endforeach
			</ul>
		</div>
    </div>
</nav>
