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
    // ログイン後に表示されるページを選択
    public function index()
    {
        $user = auth::user(); 	
        $today = Carbon::today();
        $oldPunchIn = Attendance::where('user_id',$user->id)
        ->orderBy('created_at', 'desc')
        ->first();
        $breakData = null;
        $oldDay = '';// 前回の出勤日
        //前回の出勤に関連する休憩データを取得
        if ($oldPunchIn) 
        {
            $oldTimePunchIn = new Carbon($oldPunchIn->start_time);
            $oldDay = $oldTimePunchIn->startOfDay();
            $breakData = Breaktime::where('attendance_id', $oldPunchIn->id)->latest()->first();
        }
        if($oldDay){ // ログインページからのアクセス
            if ($oldDay->isSameDay($today)){// 本日既に出勤している場合
                if (!$oldPunchIn->end_time) {
                    if($breakData){
                        if($breakData && $breakData->breakout_time){//休憩開始と終了がペアで存在する場合（未退勤）
                            return view('started', compact('user'));
                        }else if($breakData->breakin_time && !$breakData->breakout_time){// 休憩中の場合（未退勤）
                            return view('break', compact('user'));
                        }
                    }else{// 本日未休憩の場合
                        return view('started', compact('user'));
                    }
                }else{   // 退勤済の場合
                    return view('index', compact('user'));
                }
            }else{// 本日未出勤の場合
                return view('index', compact('user'));
            }
        }else{ // 新規会員登録ページからのアクセス
            return view('index', compact('user'));
        }
    }

    public function started()
    {
        $user = auth()->user(); 
        return view('started', compact('user'));
    }

    public function break()
    {
        $user = auth()->user(); 
        return view('break', compact('user'));
    }

    // 出勤アクション
    public function punchIn()
    {   
        $user = Auth::user();
        $now = Carbon::now();
        $today = Carbon::today();
        $month = intval($today->month);
        $day = intval($today->day);
        $year = intval($today->year);
        $midnight = $today->copy()->addHours(24);
        $latestAttendance = Attendance::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->first();

        if($now->greaterThanOrEqualTo($midnight)){
            if ($latestAttendance && !$latestAttendance->end_time) {
            // 最新の出勤レコードが存在し、まだ退勤していない場合、24時退勤の処理を行う
            // 出勤と退勤が同日になるよう修正している
            $latestAttendance->update([
                'end_time' => $now->copy()->setHour(24)->setMinute(0)->setSecond(0),
            ]);
            } 
            // 新しいidで出勤レコードを作成

            $attendance = Attendance::create([
                'user_id' => $user->id,
                'start_time' =>  $now->copy()->setHour(24)->setMinute(0)->setSecond(0),
                'month' => $month,
                'day' => $day,
                'year' => $year,
            ]);
        }else{
            $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'start_time' =>  $now,
                    'end_time' => null,
                    'month' => $month,
                    'day' => $day,
                    'year' => $year,
                ]);
        }
        return view('started',compact('user'));
  }


    // 退勤アクション
    public function punchOut()
    {
        $user = Auth::user();
        $latestAttendance = Attendance::where('user_id',$user->id)
        ->orderBy('created_at', 'desc')
        ->first();
        if ($latestAttendance && !$latestAttendance->end_time) {
            $latestAttendance->update([
                'end_time' => Carbon::now(),
            ]);
        }
        return view('index',compact('user'));
    }

    // 休憩開始アクション
    public function breakIn()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $today = Carbon::today();
        $attendance = $user->attendance()->latest()->first();
        $latestBreak = Breaktime::where('attendance_id', $attendance->id)
        ->orderBy('created_at', 'desc')
        ->first();
        $midnight = $today->copy()->addHours(24);
        // 24時を超えた場合、24時休憩終了0時休憩開始とする。超えていなければ通常の休憩処理を行う。
        if($now->greaterThanOrEqualTo($midnight)){
            if ($latestBreak && !$latestBreak->breakend_time) {
                $latestBreak->update([
                    'breakout_time' => $now->copy()->setHour(24)->setMinute(0)->setSecond(0),
                ]);
            // 新しいidで出勤レコードを作成
                $nextDay = $today->copy()->addDay();
                $breaktimes = Breaktime::create([
                    'attendance_id' => $attendance->id,
                    'breakin_time' =>  $now->copy()->setHour(24)->setMinute(0)->setSecond(0),
                ]);
            }else{
                $breaktimes = Breaktime::create([
                'attendance_id' => $attendance->id,
                'breakin_time' =>  $now,
                ]);
            }
        }
        return view('break', compact('user'));
    }

    // 休憩終了アクション
    public function breakOut()
    {
        $user = auth()->user();
        $attendance = $user->attendance()->latest()->first();
        $breaktime = Breaktime::where('attendance_id',$attendance->id)->latest()->first();
   
        $now = Carbon::now();
        if ($breaktime) {
            $breakIn =  new Carbon($breaktime->breakin_time);
            $workBreak = $breakIn->diffInSeconds($now);
            // 通常の休憩終了処理
            $breaktime->update([
                'breakout_time' => $now,
                'workbreak_seconds' => $workBreak
                ]);
            }
        return view('started',compact('user'));
    }

    // 日付別勤怠管理ページ
        public function daily(Request $request)
    {   
         // 日付による検索
        $selectedDate = $request->input('date', Carbon::now()->toDateString());
        $previousDate = Carbon::parse($selectedDate)->subDay()->toDateString();
        $nextDate = Carbon::parse($selectedDate)->addDay()->toDateString();
        $attendances = Attendance::whereDate('start_time', $selectedDate)->paginate(5);
        // 各出勤毎の休憩時間合計を計算(秒→時：分：秒の形へ変更)
        $breaks = Breaktime::whereIn('attendance_id', $attendances->pluck('id'))->get();
        $breakTimes = $breaks->groupBy('attendance_id')
        ->map(function($group){
            return $group->sum('workbreak_seconds');
        })
        ->map(function($value){
            $breakTimeHours = floor($value / 3600);
            $breakTimeMinutes = floor(($value % 3600) / 60);
            $breakTimeSeconds = $value % 60;
            return sprintf("%02d:%02d:%02d", $breakTimeHours, $breakTimeMinutes, $breakTimeSeconds);
        });
        // 各出勤毎の労働時間計算(秒→時：分：秒の形へ変更)
        $workTimes = [];
        foreach ($attendances as $attendance) {
            $start_time = $attendance->start_time;
            $end_time = $attendance->end_time;
            $workStart = Carbon::parse($start_time);
            $workEnd = Carbon::parse($end_time);
            $workDurationTime =  $workStart->diffInSeconds($workEnd);
            $workDurationTime -= $breaks->where('attendance_id', $attendance->id)->sum('workbreak_seconds');

            $workTimeHours = floor($workDurationTime / 3600);
            $workTimeMinutes = floor(($workDurationTime % 3600) / 60);
            $workTimeSeconds = $workDurationTime % 60;
            $workTimes[$attendance->id] = sprintf("%02d:%02d:%02d", $workTimeHours, $workTimeMinutes, $workTimeSeconds);
        }
        return view('date', compact('attendances','selectedDate', 'previousDate', 'nextDate','breakTimes','workTimes'));
    }

    public function userList()
    {
        $users = User::where('id',id)->get;
        return view('user_list', compact('users'));
    }
    public function attePerUser()
    {
        $user = auth()->user(); 
        return view('atte_per_user', compact('user'));
    }

}


