@can('show', $task)
    <tr class="task-item">
        @if ( ! $task->isCompleted() )
            <td>
                @can('update', $task)
                    <div class="checkbox m-r-xs">
                        <label>
                            <input type="checkbox" class="task-completed" data-action="{{route('contacts.taskCompleted', ['taskId' => $task->id])}}">
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
        @else
            <td><s> {{$task->typeName}} @if ($task->type == 'cancel') {{$task->description}} @else{{$task->deadline}}@endif </s></td>
        @endif
    </tr>

@endcan
