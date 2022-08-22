@extends('layouts.app')

@section('content')
<div class="container justify-content-center custom-container mb-10px">
    <div class="row">
        <div class="col-md-8 ">
            <input placeholder="What are you working on?" type="text" class="form-control" name="" id="task_input">
        </div>
        <div class="col-md-2">
            <button class="btn btn-dark" id="start" onclick="addTask()">Start</button>
            <button class="btn btn-danger d-none"  id="stop" onclick="stopTimer()">Stop</button>
        </div>
        <div id="general_timer" data-intervalId="">
            <!-- <div>Nume task</div> -->
            <div class="timer-text" id="general_timer_text">
                <span class="hours bold">00</span>:<span class="minutes bold">00</span>:<span class="seconds">00</span>
            </div>
        </div>
    </div>
</div>

<div class="container custom-container">
        <input type="hidden" name="" id="current_task" value="">
        <input type="hidden" name="" id="time_interval" value="">

        <!-- worked on TODO in the lower table-->
        <!-- <div class="col-md-2 text-center">
            <h5 class="worked">Worked: </h5><span id="time_worked">{{$time_worked}}</span>
            
        </div> -->
    <div class="table-responsive table-div">
        <table id="tasks-table" class="table table-hover ">
            <thead>
                <tr>
                    <th></th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="tasks-body">
                @if($tasks->isEmpty())
                    <tr colspsan="5">No data</tr>
                @else
                    @foreach($tasks as $key => $task)
                        <tr id="task_{{$task->id}}">
                            <td>
                                <input type="checkbox" name="" class="" id="check" onclick="changeStatus({{$task->id}})">
                            </td>
                            <td>{{$task->name}}</td>
                            <td>{{$task->status}}</td>
                            <td>1</td>
                            <td>2</td>
                            <td>3</td>
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