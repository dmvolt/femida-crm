<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="{{Auth::user()->getUrlAvatar()}}" />
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">{{Auth::user()->name}}</strong>
                             </span> <span class="text-muted text-xs block">{{Auth::user()->getPost()}} <b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{route('users.view')}}">Профиль</a></li>
                        <li class="divider"></li>
                        <li><a href="/logout">Выход</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    Sky CRM
                </div>
            </li>

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
    </div>
</nav>
