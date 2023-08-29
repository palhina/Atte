<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\Attendance;
use App\Models\Breaktime;
use Illuminate\Support\Facades\Auth;


class AttendanceController extends Controller
{
    public function index()
    {
        $user = auth()->user(); 
        return view('index', compact('user'));
    }
    public function started()
    {
        $user = auth()->user(); 
        return view('started', compact('user'));
    }public function break()
    {
        $user = auth()->user(); 
        return view('break', compact('user'));
    }
     public function home()
    {
                $user = Auth::user();
        if ($user)
         {
        $data = Attendance::where('user_id', $user->id)->first();
        } // テーブルからデータを取得
        if ($data && $data->start_time !== null) {
            return redirect()->route('started');
        } else {
            return redirect()->route('index');
        }
    }

    public function punchIn()
    {   
        $user = Auth::user();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'start_time' => now(),
        ]);
        return view('started',compact('user'));
    }
    public function punchOut()
    {
        $user = auth()->user();
        $userId = Auth::id();
        $attendance = Attendance::where('user_id',$userId)->latest()->first();
        $attendance->update([
            'end_time' => now(),
        ]);
        return view('index',compact('user'));
    }
    public function breakIn()
    {
        $user = Auth::user();
        $attendance = Attendance::first();
        $breaktime = Breaktime::create([
            'attendance_id' => $attendance->id,
            'breakin_time' => now(),
        ]);
        return view('break',compact('user'));
    }
    public function breakOut()
    {
        $user = auth()->user();
         // attendanced_idを紐づける
        $breaktime = Breaktime::where('user_id',$userId)->latest()->first();
        $breaktime->update([
            'breakout_time' => now(),
        ]);
        return view('index',compact('user'));
    }

    // 日付別勤怠管理ページへアクセス
        public function confirm()
    {
        return view('date');
    }
}
