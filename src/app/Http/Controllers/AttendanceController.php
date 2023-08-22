<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\Attendance;
use App\Models\Breaktime;
use Auth;


class AttendanceController extends Controller
{
    public function index()
    {
        return view('index');
    }
    public function confirm()
    {
        return view('date');
    }
    public function punchIn()
    {   
        $userId = Auth::id();
        Attendance::create([
            'user_id' => $userId,
            'start_time' => now(),
        ]);
        return view('started');
    }
    public function punchOut()
    {
        $userId = Auth::id();
        $attendance = Attendance::where('user_id',$userId)->latest()->first();
        $attendance->update([
            'end_time' => now(),
        ]);
        return view('index');
    }
    public function breakIn()
    {   
        $userId = Auth::id();
        Breaktime::create([
            'user_id' => $userId,
            'breakIn_time' => now(),
        ]);
        return view('break');
    }
    public function breakOut()
    {
        $userId = Auth::id();
        $breaktime = Breaktime::where('user_id',$userId)->latest()->first();
        $breaktime->update([
            'breakOut_time' => now(),
        ]);
        return view('index');
    }
    
}
