@extends('layouts.app')

@section('content')
<div class="container justify-content-center custom-container mb-10px search">
    <div class="d-flex justify-content-between align-items-center">
        <div class="w-75">
            <input placeholder="What are you working on?" type="text" class="form-control" name="" id="task_input">
        </div>
        <div class="w-10">
            <button class="btn btn-play" id="start" onclick="addTask()">Start</button>
            <button class="btn btn-danger d-none"  id="stop" onclick="stopTimer()">Stop</button>
        </div>
        <div class="w-10" id="general_timer" data-intervalId="">
            <div class="timer-text" id="general_timer_text">
                <span class="hours ">00</span>:<span class="minutes ">00</span>:<span class="seconds">00</span>
            </div>
        </div>
    </div>
</div>


<div class="container custom-container shadow">
        <input type="hidden" name="" id="current_task" value="">
        <input type="hidden" name="" id="time_interval" value="">

        <!-- worked on TODO in the lower table-->
        <!-- <div class="col-md-2 text-center">
            <h5 class="worked">Worked: </h5><span id="time_worked">{{$time_worked}}</span>
            
        </div> -->
    <div class="table-responsive table-div">
        <div class="d-flex justify-content-end">
            <div>
                <span class="">Total today: </span><span id="time_worked">{{$time_worked}}</span>
            </div>
            
        </div>
        <table id="tasks-table" class="table table-hover ">
            <thead>
                <tr>
                    <th></th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tasks-body">
                @if($tasks->isEmpty())
                    <tr colspsan="8" class="text-center">
                        <td >No data</td>
                    </tr>
                @else
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
                                <button class="btn btn-stop radius-50p" onclick="deleteTask({{$task->id}})"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @endif
                
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('page_scripts')

@stop