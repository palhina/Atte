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
        $now = Carbon::now();

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
            'id' => $latestAttendance ? $latestAttendance->id + 1 : 1,
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
        // 最新の出勤レコードを取得
            $latestAttendance = $user->attendance()->latest()->first();

            if ($latestAttendance) {
                // 現在の時刻を取得
                $currentTime = Carbon::now();

                // 24時を表すCarbonオブジェクトを作成
                $midnight = Carbon::today()->addHours(24);

                // 最新の休憩レコードを取得
                $latestBreaktime = $latestAttendance->breaktimes()->latest()->first();

                if ($latestBreaktime && $latestBreaktime->breakout_time === null) {
                    // 最新の休憩がまだ終了していない場合

                    // 24時を超えているかを確認
                    if ($currentTime->greaterThanOrEqualTo($midnight)) {
                        // 24時を超えている場合、休憩終了時間を24時に設定
                        $latestBreaktime->update([
                            'breakout_time' => Carbon::today()->addHours(24)
                        ]);
                    } else {
                        // 24時を超えていない場合、通常の休憩開始を行う
                        $breaktime = Breaktime::create([
                            'attendance_id' => $latestAttendance->id,
                            'breakin_time' => $currentTime,
                        ]);
                    }
                } else {
                    // 最新の休憩がすでに終了している場合、新しい休憩を開始する
                    $breaktime = Breaktime::create([
                        'attendance_id' => $latestAttendance->id,
                        'breakin_time' => $currentTime,
                    ]);
                }
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

    // 日付別勤怠管理ページへアクセス＋本日の日付での出退勤検索結果表示し、日付ページ遷移
        public function daily(Request $request)
    {
        $selectedDate = $request->input('date', Carbon::now()->toDateString());
        // 前日と翌日を計算
        $previousDate = Carbon::parse($selectedDate)->subDay()->toDateString();
        $nextDate = Carbon::parse($selectedDate)->addDay()->toDateString();
        $attendances = Attendance::whereDate('start_time', $selectedDate)->paginate(5);

        return view('date', compact('attendances','selectedDate', 'previousDate', 'nextDate'));
    }
}

