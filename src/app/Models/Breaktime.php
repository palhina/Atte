<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breaktime extends Model
{
    use HasFactory;
    protected $guarded = array('id');
    public static $rules = array(
        'attendance_id' => 'required',
    );
    protected $fillable = [
        'attendance_id',
        'breakin_time', 
        'breakout_time',
        'workbreak_seconds'
    ];
    
     public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
}
