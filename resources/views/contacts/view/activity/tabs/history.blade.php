<div id="tab-1" class="tab-pane ">
    <div class="panel-body">
        @foreach($contact->whereHistories()->get() as $history )
            <? $user = $history->userResponsible() ?: Auth::user() ?>

            @if($history->key == 'created_at' && !$history->old_value)
                <div class="feed-element">
                    <a href="#" class="pull-left">
                        <img alt="image" class="img-circle" src="{{$user->getUrlAvatar()}}">
                    </a>
                    <div class="media-body ">
                        <small class="pull-right">{{$contact->created_at}}</small>
                        <strong>{{$user->name}}</strong> {{\App\Model::getRuName($history->revisionable_type)}} создан<br>
                    </div>
                </div>
            @else
                <div class="feed-element">
                    <a href="#" class="pull-left">
                        <img alt="image" class="img-circle" src="{{$user->getUrlAvatar()}}">
                    </a>
                    <div class="media-body">
                        <small class="pull-right">{{$history->created_at}}</small>
                        <strong>{{$user->name}}</strong> изменил поле <strong>{{ $history->fieldName() }}</strong><br>
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
