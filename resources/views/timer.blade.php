@extends('layouts.app')

@section('content')
<div class="container justify-content-center custom-container mb-10px">
    <div class="row">
        <div class="col-md-8 ">
            <input placeholder="Enter task name" type="text" class="form-control" name="" id="task_input">
        </div>
        <div class="col-md-2">
            <button class="btn btn-dark" onclick="addTask()">Start</button>
        </div>
        <div class="col-md-2 text-center">
            <h5 class="worked">Worked: </h5><span id="time_worked">{{$time_worked}}</span>
            
        </div>
    </div>
</div>

<div class="container custom-container">
    <div class="table-responsive table-div">
        <table id="tasks-table" class="table table-hover ">
            <thead>
                <tr>
                    <th></th>
                    <th>Task Name</th>
                    <th>Status</th>
                    <th>Time spent</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tasks-body">
                @foreach($tasks as $key => $task)
                    <tr id="task_{{$task->id}}">
                        <td>
                            <input type="checkbox" name="" class="" id="check" onclick="changeStatus({{$task->id}})">
                        </td>
                        <td>{{$task->name}}</td>
                        <td>{{$task->status}}</td>
                        <td>
                            {!!$task->TotalTimeHtml!!}
                        </td>
                        <td>
                        <button class="btn btn-play radius-50p" id="start_{{$task->id}}" onclick="startTimer('{{$task->id}}', '{{$task->time}}')"><i class="fa fa-play" aria-hidden="true"></i></button>
                        <button class="btn btn-play radius-50p d-none" id="stop_{{$task->id}}" onclick="stopTimer({{$task->id}})"><i class="fa fa-stop" aria-hidden="true"></i></button>
                        <button class="btn btn-stop radius-50p" onclick="deleteTask({{$task->id}})"><i class="fa fa-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="general_timer" data-intervalId="">
    <div>Nume task</div>
    <div class="timer-text" id="general_timer_text">
        <!-- <span><i class="fa fa-clock-o" id="clock-icon"></i></span> -->
        <button class="btn btn-success radius-50p" id="start_{{$task->id}}" onclick="startTimer('{{$task->id}}', '{{$task->time}}')">
            <i class="fa fa-play"></i>
        </button>
        <button class="btn btn-success radius-50p d-none" id="stop_{{$task->id}}" onclick="stopTimer({{$task->id}})">
            <i class="fa fa-stop"></i>
        </button>
        <span class="hours bold">00</span>:<span class="minutes bold">00</span>:<span class="seconds">00</span>
    </div>
</div>
@endsection

@section('page_scripts')

@stop