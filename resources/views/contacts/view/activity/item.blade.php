@if ($activity->type == 'Добавлен комментарий')
<div class="feed-element-comment">
@else
<div class="feed-element">
@endif
    <a href="#" class="pull-left">
        <img alt="image" class="img-circle" src="{{$activity->user->getUrlAvatar()}}">
    </a>
    <div class="media-body ">
        <small class="pull-right">2h ago</small>
        <strong>{{$activity->user->name}}</strong> {{$activity->type}}<br>
        <small class="text-muted">{{$activity->created_at}}</small>
        <div class="well markdown-text">
            {{$activity->text}}
        </div>
    </div>
</div>
