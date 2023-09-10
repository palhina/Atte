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
    // ログイン後のページ表示(出勤前、出勤後、休憩開始後のページ分岐)
    public function index()
    {
        $user = auth::user(); 	
        // 出勤データ取得
        // ログイン者の一番最新の出勤履歴を取得しoldPunchIn変数に格納
        $oldPunchIn = Attendance::where('user_id',$user->id)
        ->orderBy('created_at', 'desc')
        ->first();
        $breakData = null; // 休憩データを初期化

        //１．前回の出勤日はいつだったか？ 
        $oldday = '';
        //もしユーザーが以前に出勤していた際(oldPunchInが存在していた場合)の処理 
        if ($oldPunchIn) 
        {
            //oldPunchInの出勤時刻(start_time)をCarbonに変換、oldTimeIn変数に格納 
            $oldTimePunchIn = new Carbon($oldPunchIn->start_time);
            // 上記で格納したoldTimeInの日付情報を取得、時刻を０時０分０秒にしてoldDay変数へ格納
            $oldDay = $oldTimePunchIn->startOfDay();
            // 前回の出勤に関連する休憩データを取得
            $breakData = Breaktime::where('attendance_id', $oldPunchIn->id)->latest()->first();
        }
        // ２．今日の日付取得
        $today = Carbon::today();


        // １と２が同じ時（既に今日出勤が押されている場合）の分岐
        if ($oldDay->isSameDay($today)){
            // かつ、退勤時間が存在しない場合
            if (!$oldPunchIn->end_time) {
                if($breakData){
                // 休憩開始データ,休憩終了データがある場合
                    if($breakData && $breakData->breakout_time){
                        return view('started', compact('user'));
                // 休憩開始時間が存在し、休憩終了時間は存在しない場合
                    }else if($breakData->breakin_time && !$breakData->breakout_time){
                        return view('break', compact('user'));
                    }
                }else{
                // 休憩開始データがない場合
                    return view('started', compact('user'));
                }
            }else{
                // 退勤時間が存在していた場合の処理
                return view('index', compact('user'));
            }
        }
        // 今日まだ出勤開始を押していない場合
        else{
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

        // 現在の年月日を整数で抽出（日付別勤怠ページへの前処理）
        $today = Carbon::today();
        $month = intval($today->month);
        $day = intval($today->day);
        $year = intval($today->year);

     // ユーザーの最新の出勤レコードを取得
        $latestAttendance = Attendance::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->first();

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
            'start_time' => Carbon::now(),
            'month' => $month,
            'day' => $day,
            'year' => $year,
            'attendance_id' => $latestAttendance ? $latestAttendance->attendance_id + 1 : 1,
        ]);
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
        // 最新の出勤レコードが存在し、まだ退勤していない場合、退勤処理を行います
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
        if ($user) {
            $attendanceId = $user->attendance()->latest()->first()->id;
            $breaktime = Breaktime::create([
                'attendance_id' => $attendanceId,
                'breakin_time' => Carbon::now(),
            ]);
            return view('break',compact('user'));
        }
    }

    // 休憩終了アクション
    public function breakOut()
    {
        $user = auth()->user();
        $attendance = $user->attendance()->latest()->first();
        $breaktime = Breaktime::where('attendance_id',$attendance->id)->latest()->first();

         // 休憩が翌日にまたがる場合の処理     
        $now = Carbon::now();
        if ($breaktime) {
            $breakIn =  new Carbon($breaktime->breakin_time);
            $workBreak = $breakIn->diffInSeconds($now);
        
            if ($now->lessThan($breaktime->breakin_time)) {
                $nextDay = $breaktime->breakin_time->addDay();
                $breaktime->update([
                    'breakout_time' => $nextDay->setTime(0, 0), // 休憩終了を0時に設定
                ]);
                // 新しい休憩レコードを作成
                $newBreaktime = new Breaktime();
                $newBreaktime->attendance_id = $attendance->id;
                $newBreaktime->breakin_time = $nextDay->setTime(0, 0); // 勤怠開始を0時に設定
                $newBreaktime->breakout_time = $now; // 現在の時間を勤怠終了として設定
                $newBreaktime->save();
            } else {
            // 通常の休憩終了処理
                $breaktime->update([
                    'breakout_time' => $now,
                    'workbreak_seconds' => $workBreak
                    ]);
            }
        }
        return view('started',compact('user'));
    }

    // 日付別勤怠管理ページへアクセス
        public function confirm()
    {
        $user = auth()->user();
        $attendances = Attendance::with('user')->paginate(5);
        $attendanceId = $attendances->pluck('id');
        $breaktimes = Breaktime::where('attendance_id',$attendanceId)->get();
        $breakDurationSeconds = $breaktimes->sum('workbreak_seconds');
        // 現在の年月日を取得し表示する、あとで検索機能作成時に使用  
        $today = Carbon::today();	
        $month = intval($today->month);	
        $day = intval($today->day);	
        $format = $today->format('Y-m-d');

        $breakTimeHours = floor($breakDurationSeconds / 3600);
        $breakTimeMinutes = floor(($breakDurationSeconds % 3600) / 60);
        $breakTimeSeconds = $breakDurationSeconds % 60;
        $breakTimes = sprintf("%02d:%02d:%02d", $breakTimeHours, $breakTimeMinutes, $breakTimeSeconds);

        
        //当日の勤怠を検索し取得	
        $items = Attendance::GetMonthAttendance($month)->GetDayAttendance($day)->get();
        return view('date', compact('user', 'attendances', 'items', 'day', 'format','breakTimes'));
    }
    // 日付別勤怠ページ検索機能
    public function daily(Request $request) 
    {
        $query = $request->input('search_date');
        $results = Attendance::whereDate('start_time',$query)->get();
        session(['results' => $results]);
        return redirect('/date');
    }
}

