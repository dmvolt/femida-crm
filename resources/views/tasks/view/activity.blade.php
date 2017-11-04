<div>
    <form action="{{route('tasks.addComment', ['leadId' => $task->id])}}" method="POST">
        {!! csrf_field() !!}
        <textarea id="comment" rows="3" name="comment" class="md-input" data-provide="markdown" required></textarea>
        <button class="btn btn-primary m pull-right"> добавить</button>
    </form>

    <div class="feed-activity-list" style="margin-top: 60px;">
        <div class="tabs-container">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">История изменений</a></li>
                <li class=""><a data-toggle="tab" href="#tab-2" aria-expanded="false">Комментарии</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        @foreach($task->revisionHistory()->orderBy('id', 'desc')->get() as $history )
                            @if($history->key == 'created_at' && !$history->old_value)
                                <div class="feed-element">
                                    <a href="#" class="pull-left">
                                        <img alt="image" class="img-circle" src="{{$history->userResponsible()->getUrlAvatar()}}">
                                    </a>
                                    <div class="media-body ">
                                        <small class="pull-right">{{$task->created_at}}</small>
                                        <strong>{{$history->userResponsible()->name}}</strong> объект создан<br>
                                    </div>
                                </div>
                            @else
                                <div class="feed-element">
                                    <a href="#" class="pull-left">
                                        <img alt="image" class="img-circle" src="{{$history->userResponsible()->getUrlAvatar()}}">
                                    </a>
                                    <div class="media-body">
                                        <small class="pull-right">{{$history->created_at}}</small>
                                        <strong>{{$history->userResponsible()->name}}</strong> изменил поле <strong>{{ $history->fieldName() }}</strong><br>
                                        <div class="well">
                                            {!!  $history->oldValue()  !!}
                                        </div>
                                        Изменено:
                                        <div class="well">
                                            {!! $history->newValue()  !!}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="feed-activity-list" style="margin-top: 60px;">
                        @foreach($task->activities->take(5) as $_activity)
                            @include('tasks.view.activity.item', ['activity' => $_activity])
                        @endforeach
                    </div>

                    <button class="btn btn-primary btn-block m ladda-button" data-style="slide-up" id="activity-btn"><i class="fa fa-arrow-down"></i> Показать еще</button>
                    <script type="text/javascript">
                        $(document).ready(function ()
                        {
                            $("#comment").markdown({autofocus:false, savable:false, preview:false, language:'ru', hiddenButtons: 'cmdPreview', disabledButtons: 'cmdPreview'});

                            var activityBtn = $('#activity-btn').ladda();

                            activityBtn.on('click', function () {
                                activityBtn.ladda( 'start' );

                                $.get( "<?=route('tasks.activity')?>", { task_id: "<?=$task->id?>", offset: $('.feed-element').size() }, function (response) {
                                    $('.feed-activity-list').append(response);
                                    activityBtn.ladda('stop');
                                } );
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>