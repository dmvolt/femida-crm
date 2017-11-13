@can('show', $task)
    <tr class="task-item" id="task_item_{{$task->id}}">
        @if ( $task->completed == 'no' )
            <td>
                @can('update', $task)
                    <div class="checkbox m-r-xs">
                        <label>
							@if ( $task->type == 'appointment' )
								<input type="checkbox" id="task-appointment-check-{{$task->id}}" class="task-appointment-completed">
							@else
								<input type="checkbox" class="task-completed" data-action="{{route('contacts.taskCompleted', ['taskId' => $task->id])}}">
							@endif
                            {{$task->typeName}} @if ($task->type == 'cancel') {{$task->description}} @else{{$task->deadline}}@endif
                        </label>
                    </div>
                @else
                    {{$task->typeName}} @if ($task->type == 'cancel') {{$task->description}} @else{{$task->deadline}}@endif
                @endcan
            </td>
            <td>
                @can('delete', $task)
                    <button data-action="{{route('tasks.remove', ['taskId' => $task->id])}}" class="remove-task btn btn-default btn-xs"><i class="fa fa-trash"></i></button>
                @endcan
            </td>
		@elseif( $task->completed == 'canceled')
            <td> {{$task->typeName}} @if ($task->type == 'cancel') {{$task->description}} @else{{$task->deadline}}@endif ! встреча не проведена</td>
        @else
            <td><s> {{$task->typeName}} @if ($task->type == 'cancel') {{$task->description}} @else{{$task->deadline}}@endif </s></td>
        @endif
    </tr>
	<tr id="task-appointment-complete-template-{{$task->id}}" style="display: none;">
		<td colspan="2">
			<div class="panel panel-success task-appointment-complete-item" style="margin-top: 15px;">
				<div class="panel-heading">Встреча {{$task->deadline}}</div>
				<div class="panel-body">
					<form class="form-inline" id="task-appointment-complete-form-{{$task->id}}" action="{{route('contacts.taskAppointmentCompleted')}}">
						{{csrf_field()}}
						<input type="hidden" name="task_id" value="{{$task->id}}">
						<div class="form-group" style="">
							<label class="control-label">Была ли фактически проведена встреча?</label>
							<select class="form-control form-control" name="complete_status">
								<option value="complete">Проведена</option>
								<option value="canceled">Не проведена</option>
							</select>
						</div>

						<div class="form-group">
							<button class="btn btn-primary m" id="button-task-appointment-complete-save-{{$task->id}}"> применить</button>
							<button class="btn btn-default m button-task-appointment-complete-cancel" id="button-task-appointment-complete-cancel-{{$task->id}}"> отмена</button>
						</div>
					</form>
				</div>
			</div>
		</td>
	</tr>
	<script type="text/javascript">
		$('#task-appointment-check-{{$task->id}}').on('change', function () {
			$('#task-appointment-complete-template-{{$task->id}}').show();
		});
		
		$('#button-task-appointment-complete-save-{{$task->id}}').on('click', function () {
			
			var data = $('#task-appointment-complete-form-{{$task->id}}').serializeArray().reduce(function(obj, item) {
				obj[item.name] = item.value;
				return obj;
			}, {});
			
			$.ajax({
				type: "POST",
				url: $('#task-appointment-complete-form-{{$task->id}}').attr('action'),
				data: data,
				success: function (content)
				{
					$('#task_item_{{$task->id}}').remove();
					$('#task-appointment-complete-template-{{$task->id}}').remove();
                    $('#task-list').append(content);
				}
			});
			
			$('#task-appointment-complete-template-{{$task->id}}').hide();
			return false;
		});
		
		$('#button-task-appointment-complete-cancel-{{$task->id}}').on('click', function(){
			$('#task-appointment-complete-template-{{$task->id}}').hide();
			return false;
		});
	</script>
@endcan
