<div class="modal inmodal" id="showModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated">
            <div class="modal-header">
                <h4 class="modal-title">Контакт</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<style>
    .modal-dialog {
        width: 85%;
        height: 90%;
        padding: 1%;
    }
    .modal-content {
        height: auto;
        min-height: 100%;
        border-radius: 0;
    }
</style>
<script type="text/javascript">
    $(function ()
    {
        var contacts =
            {
                modal: null,
                urlShow: null,
                self: null,

                init : function (url)
                {
                    var self = this;
                    this.urlShow = url;
                    this.modal = $('#showModal');
					

                    this.hash = window.location.hash;
                    if( ~self.hash.indexOf('#contact') )
                    {
                        var id = self.hash.replace('#contact','');
                        if (id)
                        {
                            this.showContact(id);
                        }
                    }
                },

                showContact: function (contactId)
                {
                    var self = this;
                    var url = self.urlShow;
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {id: contactId, _token: window.Laravel.csrfToken, ref: window.location.href},
                        success: function (content)
                        {
                            self.modal.find('.modal-content').html(content);
							@if (Session::has('param'))
							{{--	{{Session::forget('param')}} --}}
								self.modal.modal({
									backdrop: 'static',
								});
								// $('.btn.btn-primary.m.pull-right').hide();
								$('.btn-default.btn-sm').hide();
							@endif
							
							var data;
							var url1 = "/tasks/getId/"+contactId;
							$.ajax({
								async: false,
								url: url1,
								success: function (_data) {
									data = _data;
								}
							});
							if (data == 0) {
								self.modal.modal({
									backdrop: 'static',
								});
							}
							
                            self.modal.modal('show');

                            $('.datetime-input').datetimepicker({
                                format: 'yyyy-mm-dd hh:ii',
                                language: 'ru',
                                todayBtn: 'linked',
                                autoclose: true,
                                orientation: "auto"
                            });
                        }
                    });
                }
            } ;

        contacts.init('{{route('contacts.restShow')}}');

        $('.client-link').on('click', function ()
        {
            var id = ($(this).attr('data-id'));
            window.location.hash = 'contact' + id;

            contacts.showContact($(this).attr('data-id'));
            return false;
        });

        var modal = $('#showModal');

        modal.on('click', '.task-buttons .btn', function ()
        {
            $('#task-list').hide();
            $('#task-form').show();
            $('#task-form').find('.input-type').val($(this).attr('data-type'));
			if ($(this).attr('data-type') == 'cancel') {
				//alert($('.datetime-input').attr('class'));
				$('.datetime-input').hide();
				$('.description-input').show();
				$('.control-label').text('Описание');
			} else {
				$('.datetime-input').show();
				$('.description-input').hide();
				$('.control-label').text('Дата');
			}

        });

        modal.on('click', '.task-cancel.btn', function ()
        {
            $('#task-list').show();
            $('#task-form').hide();
        });

        modal.on('click', '.task-create.btn', function ()
        {
            $('#task-list').show();
            $('#task-form').hide();
			$('#showModal').modal({
				backdrop: 'true',
			});
			$('.btn-default.btn-sm').show();

            $.ajax({
                type: "POST",
                url: $('.task-create.btn').attr('href'),
                data: {type: $('#task-form').find('.input-type').val(), _token: window.Laravel.csrfToken, deadline: $('#task-form').find('#deadline').val(), description: $('#task-form').find('#description').val(), user_id: $('#task-form').find('.user-select').val() },
                success: function (content)
                {
                    $('#task-list').append(content);
					/*
					$('.modal-content').hide();
					$('.modal-backdrop').hide();
					*/
					// location.href = '/tasks';
					if ($('#task-form').find('.input-type').val() == 'cancel')
						window.location.href = '';
					else
						location.reload();
                },
            });

            return false;
        });

        modal.on('change', '.task-completed', function ()
        {
            var self = $(this);
            $.ajax({
                type: "POST",
                url: self.attr('data-action'),
                data: {_token: window.Laravel.csrfToken},
                success: function (content)
                {
                    self.closest('.task-item').remove();
                    $('#task-list').append(content);
                },
            });
            return false;
        });

        modal.on('hidden.bs.modal', function () {
				window.location.hash = '';
        })
    });
</script>
<div class="modal inmodal" id="showModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content animated">
            <div class="modal-header">
                <h4 class="modal-title">Добавить напоминание к задаче</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>
<style>
    .modal-dialog {
        width: 85%;
        height: 90%;
        padding: 1%;
    }
    .modal-content {
        height: auto;
        min-height: 100%;
        border-radius: 0;
    }
</style>
