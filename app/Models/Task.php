<?php

namespace App\Models;

use App\Traits\Filterable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'name',
        'status',
        'total_time'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
    
    ];

    public function getTotalTimeHtmlAttribute()
    {
        $data = $this->formatTime(null);
        $html = '<div id="timer_'.$this->id.'" data-intervalId="">
                    <p class="timer-text">
                        <span class="hours">'.$data['hours'].'</span>:<span class="minutes">'.$data['min'].'</span>:<span class="seconds">'.$data['sec'].'</span>
                    </p>
                </div>';
        return $html;
    }

    public function getTimeAttribute()
    {
        return $this->total_time;
    }

    public function formatTime($time)
    {
        $value = is_null($time) ? (int)$this->total_time : $time;
        $data  = [];

        $data['sec']   = str_pad($value % 60, 2, "0", STR_PAD_LEFT);
        $data['min']   = str_pad(floor(($value / 60) % 60), 2, "0", STR_PAD_LEFT);
        $data['hours'] = str_pad(($value / 3600) % 60, 2, "0", STR_PAD_LEFT);

        return $data;
    }

    /**
     * [coupons description]
     * @return [type] [description]
     */
    public function history() {
        return $this->hasMany('App\Models\TaskTime');
    }
}
