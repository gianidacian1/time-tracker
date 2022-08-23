@extends('layouts.app')

@section('content')
<div class="container no-gutter d-none" id="error-div">
    <div class="alert alert-danger" role="alert" id="error-message">
        
    </div>
</div>
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
                <span class="">Total today: </span><div id="time_worked">{{$time_worked}}</div>
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
                                <input type="checkbox" data-toggle="tooltip" data-placement="top" title="Complete task" name="" class="" id="check" onclick="changeStatus({{$task->id}})">
                            </td>
                            <td>
                                <span onclick="showHistory({{$task->id}}, 'show')" id="show_history_{{$task->id}}" class="">
                                    <i class="fa fa-plus-square "  data-toggle="tooltip" data-placement="top" title="Show task history"></i>
                                </span>
                                <span onclick="showHistory({{$task->id}}, 'hide')" id="hide_history_{{$task->id}}" class=" d-none">
                                    <i class="fa fa-minus-square "  data-toggle="tooltip" data-placement="top" title="Show task history"></i>
                                </span>
                                {{$task->name}}
                            </td>
                            <td>{{$task->status}}</td>
                            <td>
                                {!!$task->TotalTimeHtml!!}
                            </td>
                            <td>
                                <button class="btn btn-stop " onclick="deleteTask({{$task->id}})"><i class="fa fa-trash" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        @if($task->history)
                            
                            <tr id="child_header_{{$task->id}}" class=" border-top d-none">
                                <th></th>
                                <th></th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </tr>
                            @foreach($task->history as $key=>$history)
                                <tr id="" class="{{$key+1 == count($task->history) ? 'border-bottom' : ''}} d-none child_body_{{$task->id}}">
                                    <td></td>
                                    <td></td>
                                    <td>{{$history->start_date}}</td>
                                    <td>{{$history->end_date}}</td>
                                </tr>
                            @endforeach
                           
                        @endif
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>

@endsection

@section('page_scripts')

@stop