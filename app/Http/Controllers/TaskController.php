<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskTime;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreTaskRequest;
use Validator;

class TaskController extends Controller
{
//backlog
// To Do
// In Progress
// Ready for Testing
// Testing
// Move Live
// Complete

//priority
//critical, high, normal, low

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the task list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $tasks   = Task::where('user_id', $user_id)->orderBy('id')->get();
        
        $time_worked = 0;
        $times = TaskTime::whereDate('start_date', date('Y-m-d'))->whereNotNull('end_date')->get();

        foreach ($times as $key => $t) {
            $time_worked += strtotime($t->end_date) - strtotime($t->start_date);
        }

        $time_worked = $this->formatTime($time_worked);
        
        return view('timer')->with('tasks', $tasks)
                            ->with('time_worked', $time_worked);
    }

    /**
     * Show the task listk for api.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function listTasks()
    {
        $user_id = auth()->user()->id;
        $tasks   = Task::where('user_id', $user_id)->orderBy('id')->get();
        // $data   = view('timer')->with('tasks', $tasks)->render();
       
        return response()->json(['data' =>$d]);
    }

    /**
     * Create or update task
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {   
        $inputs = $request->all();
        
        //validation
        $rules = [
            'name'    => 'required',
        ];

        $messages = [
            'name.required'   => 'The name field is required.',
        ];

        $validator = Validator::make($inputs, $rules, $messages);

        if ($validator->fails()) {
            return response()->json(['success'=> false, 'errors'=> $validator->messages(), 'data' => [] ]);
        }

        $data['user_id'] = auth()->user()->id;
        $data['name']    = $request->name ? $request->name : '';
        $result          = ['succes' =>false];
        $new             = false;

        if(count($data)) {
            try {
                $task = Task::where("name", $data["name"])->first();

                if(is_null($task)) {
                    $task = Task::create($data);
                    $new = true;
                }

                $result = ['succes' => true, 'data' => $task, 'new' => $new];
            } catch (\Throwable $th) {
                $result = ['succes' => false, 'message' => $th->getMessage()];
            }
        }

        return response()->json($result);
    }

    /**
     * Update task fields.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update(Request $request)
    {
        $task_id = $request->task_id ? $request->task_id : '';
        $result  = ['succes' =>false];
        $fields  = $request->except('task_id');

        if($task_id) {
            try {
                $task = Task::find($task_id);
                $task->update($fields);

                $result = ['succes' => true, 'message' => 'Task succesfully updated.'];
            } catch (\Throwable $th) {
                $result = ['succes' => false, 'message' => $th->getMessage()];
            }
        }

        return response()->json($result);
    }

    /**
     * Update task time -> start/stop timer.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function updateTime(Request $request)
    {
        $task_id    = $request->task_id    ? $request->task_id    : '';
        $start_date = $request->start_date ? $request->start_date : '';
        $end_date   = $request->end_date   ? $request->end_date   : '';
        $action     = $request->action     ? $request->action     : '';

        $result     = ['succes' => false];
        $duration   = 0;

        if($task_id) {
            try {
                if($action == 'stop-time') {
                    $task_time = TaskTime::where('task_id', $task_id)->orderBy('id', 'desc')->first();
                    $task_time->update(['end_date' => $end_date]);

                    $task     = Task::find($task_id);

                    if($task) {
                        $duration = strtotime($end_date) - strtotime($task_time->start_date);

                        $task->total_time =  (int)$task->total_time +(int)$duration;
                        $task->save();
                    }
                } else {

                    $data = [
                        'task_id'    => $task_id,
                        'start_date' => $start_date
                    ];
                   
                    TaskTime::create($data);
                }
                // $task->update($data);
                $result = ['succes' => true, 'message' => 'Task succesfully updated.', 'total_time' => $task->total_time ?? 0, 'history' => ['start_date'=> $task_time->start_date, 'end_date'=> $end_date]];
            } catch (\Throwable $th) {
                $result = ['succes' => false, 'message' => $th->getMessage()];
                Log::error( $th->getMessage() );
            }
        }

        return response()->json($result);
    }

    /**
     * Soft Delete task.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function delete(Request $request)
    {
        $id = $request->task_id ? $request->task_id : '';
        $result = ['success' => false];

        if($id) {
            try {
                $task = Task::find($id);
                $task->delete();
                $time_recorded = TaskTime::where('task_id', $id)->delete();

                $result = ['success' => true, 'message' => 'Task succesfully deleted.'];
            } catch (\Throwable $th) {
                $result = ['success' => false, 'message' => $th->getMessage()];
            }
        }

        return response()->json($result);
    }

    /**
     * Helper function for formating time
     */
    public function formatTime($time)
    {
        $value = is_null($time) ? (int)$this->total_time : $time;
        $data  = [];

        $sec   = str_pad($value % 60, 2, "0", STR_PAD_LEFT);
        $min   = str_pad(floor(($value / 60) % 60), 2, "0", STR_PAD_LEFT);
        $hours = str_pad(($value / 3600) % 60, 2, "0", STR_PAD_LEFT);

        $time = $hours . 'h ' . $min . 'min';
        return $time;
    }

    /**
     * Get history of a task
     */
    public function getHistory(Request $request) {
        $task_id = $request->task_id ? $request->task_id : '';
        $result  = ['success' => false];

        if($task_id) {
            $history = TaskTime::where('task_id', $task_id)->orderBy('start_date')->get();

            $result = ['success' => true, 'data' => $history];
        }

        return response()->json($result);
    }
}
