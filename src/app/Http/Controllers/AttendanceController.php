<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
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
        $latestAttendance = Attendance::where('user_id',$user->id)
        ->orderBy('created_at', 'desc')
        ->first();
        $breakData = null;
        
        if ($latestAttendance) //最新出勤データがある場合
        {
            //最新出勤に関連する休憩データを取得
            $breakData = Breaktime::where('attendance_id', $latestAttendance->id)->latest()->first();
            // 最新出勤はあるが退勤データがない場合
            if (!$latestAttendance->end_time) {
                if($breakData){
                    if($breakData->breakout_time){//休憩開始と終了がペアで存在する場合（未退勤）
                        return view('started', compact('user'));
                    }else{// 休憩中の場合
                        return view('break', compact('user'));
                    }
                }else{// 本日未休憩の場合
                    return view('started', compact('user'));
                }
            }else{   // 退勤済の場合
                    return view('index', compact('user'));
            }
        }else{// 未出勤の場合
            return view('index', compact('user'));
        }
    }

    // 出勤開始ボタンを押した後のページ(休憩開始、休憩終了ボタンが表示)
    public function started()
    {
        $user = auth()->user(); 
        return view('started', compact('user'));
    }

    // 休憩開始ボタンを押した後のページ(休憩終了ボタンが表示)
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
        $month = $now->month;
        $day = $now->day;
        $latestAttendance = Attendance::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->first();
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'start_time' =>  $now,
            'end_time' => null,
            'month' => $month,
            'day' => $day,
        ]);
        return view('started',compact('user'));
    }


    // 退勤アクション
    public function punchOut()
    {
        $user = Auth::user();
        $now = Carbon::now();
        $month = $now->month;
        $day = $now->day;
        $latestAttendance = Attendance::where('user_id',$user->id)
        ->orderBy('start_time', 'desc')
        ->first();
        if ($latestAttendance){
            $latestAttendanceDay = Carbon::parse($latestAttendance->start_time);
            $endOfDay = $latestAttendanceDay->copy()->addHours(24);
            $startOfDay = $now->copy()->startOfDay();
            // もし出勤当日中の退勤である場合は、通常の退勤操作を行う。
            // もし出勤と退勤が別日であれば出勤日24時退勤、本日０時出勤開始としてから退勤操作を行う。
            if($latestAttendanceDay->isSameDay($now)){
                $latestAttendance->update([
                    'end_time' => $now,
                ]);
            }else{
                $latestAttendance->update([
                    'end_time' => $endOfDay(),
                ]);
                $attendance = Attendance::create([
                    'user_id' => $user->id,
                    'start_time' => $startOfDay(),
                    'end_time' => $now,
                    'month' => $month,
                    'day' => $day,
                ]);
            }
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
        $breaktimes = Breaktime::create([
        'attendance_id' => $attendance->id,
        'breakin_time' =>  $now,
        ]);
        return view('break', compact('user'));
    }

    // 休憩終了アクション
    public function breakOut()
    {
        $user = auth()->user();
        $attendance = $user->attendance()->latest()->first();
        $latestBreak = Breaktime::where('attendance_id', $attendance->id)
        ->orderBy('created_at', 'desc')
        ->first();
        $now = Carbon::now();
        if ($latestBreak) {
            $breakIn =  Carbon::parse($latestBreak->breakin_time);
            $endOfBreakDay = $breakIn->copy()->addHours(24);
            $startOfDay = $now->copy()->startOfDay();
            $workBreak = $breakIn->diffInSeconds($now);
            if($breakIn->isSameDay($now)){
                $latestBreak->update([
                    'breakout_time' => $now,
                    'workbreak_seconds' => $breakIn->diffInSeconds($now),
                    ]);
            }else{
                $latestBreak->update([
                    'breakout_time' => $endOfBreakDay(),
                    'workbreak_seconds' => $breakIn->diffInSeconds($endOfBreakDay),
                ]);
                $breaktimes = Breaktime::create([
                    'attendance_id' => $attendance->id,
                    'breakin_time' =>  $startOfDay,
                    'breakout_time' => $now,
                    'workbreak_seconds' => $startOfDay->diffInSeconds($now),
                ]);                
            }
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

    // ユーザー一覧表示
    public function userList()
    {
        $users = User::select('name')->paginate(5);
        return view('user_list',compact('users'));
    }

    // ユーザー別勤怠一覧表示
    public function attePerUser()
    {
        $userId = auth()->user()->id; 
        $user = User::find($userId);
        $attendances = Attendance::where('user_id',$userId)->orderBy('created_at', 'desc')
        ->paginate(5);
        
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
        return view('atte_per_user', compact('attendances','user','breakTimes','workTimes'));
    }

}


