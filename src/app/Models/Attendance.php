<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 
        'start_time', 
        'end_time',
        'year',
        'month',
        'day',
    ];

     public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function breaktimes()
    {
        return $this->hasMany(Breaktime::class);
    }

}
