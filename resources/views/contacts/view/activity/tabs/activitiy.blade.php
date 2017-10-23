<div id="tab-2" class="tab-pane">
    <div class="feed-activity-list-comments" style="margin-top: 60px;">
		@if ($contact->activities->count() == 0)
			Нет комментариев
		@else
			@foreach($contact->activities->take(5) as $_activity)
				@include('contacts.view.activity.item', ['activity' => $_activity])
			@endforeach
		@endif
    </div>

    <button class="btn btn-primary btn-block m ladda-button" data-style="slide-up" id="activity-btn"><i class="fa fa-arrow-down"></i> Показать еще</button>
    <script type="text/javascript">
        $(document).ready(function ()
        {
			if ($('.feed-element-comment').length >= {{$contact->activities->count()}}) {
				$('.ladda-button').hide();
			}
			
            $("#comment").markdown({autofocus:false, savable:false, preview:false, language:'ru', hiddenButtons: 'cmdPreview', disabledButtons: 'cmdPreview'});

            var activityBtn = $('#activity-btn').ladda();

            activityBtn.on('click', function () {
                activityBtn.ladda( 'start' );

                $.get( "<?=route('contacts.activity')?>", { contact_id: "<?=$contact->id?>", offset: $('.feed-element-comment').length }, function (response) {
                    $('.feed-activity-list-comments').append(response);
					if ($('.feed-element-comment').length >= {{$contact->activities->count()}}) {
						$('.ladda-button').hide();
					}
                    activityBtn.ladda('stop');
                } );
            });
        });
    </script>
</div>
