@foreach($tasks as $key => $task)
    <tr id="task_{{$task->id}}">
        <td>
            <input type="checkbox" name="" class="" id="">
        </td>
        <td>{{$task->name}}</td>
        <td>{{$task->status}}</td>
        <td>
            {!!$task->TotalTimeHtml!!}
        </td>
        <td>
        <button class="btn btn-success radius-50p" id="start_{{$task->id}}" onclick="startTimer('{{$task->id}}', '{{$task->time}}')">
            <i class="fa fa-play"></i>
        </button>
        <button class="btn btn-success radius-50p d-none" id="stop_{{$task->id}}" onclick="stopTimer({{$task->id}})">
            <i class="fa fa-stop"></i>
        </button>
        <button class="btn btn-danger radius-50p" onclick="deleteTask({{$task->id}})"><i class="fa fa-times"></i></button>
        </td>
    </tr>
@endforeach