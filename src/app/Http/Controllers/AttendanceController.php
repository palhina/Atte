<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\Attendance;
use App\Models\Breaktime;
use Carbon\Carbon;
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

    public function punchIn()
    {   
        $user = Auth::user();

        // 現在の年月日を整数で抽出（日付別勤怠ページへの前処理）
        $today = Carbon::today();
        $month = intval($today->month);
        $day = intval($today->day);
        $year = intval($today->year);

        $attendance = Attendance::create([
            'user_id' => $user->id,
            'start_time' => Carbon::now(),
            'month' => $month,
            'day' => $day,
            'year' => $year,
        ]);
        return view('started',compact('user'));

    }
    public function punchOut()
    {
        $user = Auth::user();
        $attendance = Attendance::where('user_id',$user->id)->latest()->first();
        // 多分ここに、日付が本日と違った場合(24時を回ったら)みたいな処理が入ると思われる
        $attendance->update([
            'end_time' => Carbon::now(),
        ]);
        return view('index',compact('user'));
    }
    public function breakIn()
    {
        $user = Auth::user();
        if ($user) {
            $attendanceId = $user->attendance->id;
            $breaktime = Breaktime::create([
                'attendance_id' => $attendanceId,
                'breakin_time' => Carbon::now(),
            ]);
            return view('break',compact('user'));
        }
    }
    public function breakOut()
    {
        $user = auth()->user();
        $attendance = $user->attendance;
        $breaktime = Breaktime::where('attendance_id',$attendance->id)->latest()->first();
        $breaktime->update([
            'breakout_time' => Carbon::now(),
        ]);
        return view('started',compact('user'));
    }

    // 日付別勤怠管理ページへアクセス
        public function confirm()
    {
        $user = Auth::user();
        $attendances = Attendance::with('user')->paginate(5);
        return view('date', compact('user', 'attendances'));
    }
}
