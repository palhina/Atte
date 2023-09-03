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
        $user = auth::user(); 	
        //以下追加処理…同日に２回出勤が押せない処理：リダイレクト先設定いじってstartedページへ飛ばすようにする
        // ログイン者の一番最新の出勤履歴を取得しoldPunchIn変数に格納
        $oldPunchIn = Attendance::where('user_id',$user->id)->latest()->first();
        // 以下、1日の出勤を1回にする処理
        //１．前回の出勤日はいつだったか？ 
        $oldday = '';
        //もしユーザーが以前に出勤していた際(oldPunchInが存在していた場合)の処理 
        if ($oldPunchIn) 
        {
            //oldPunchInの出勤時刻(start_time)をCarbonに変換、oldTimeIn変数に格納 
            $oldTimeIn = new Carbon($oldPunchIn->start_time);
            // 上記で格納したoldTimeInの日付情報を取得、時刻を０時０分０秒にしてoldDay変数へ格納
            $oldDay = $oldTimeIn->startOfDay();
        }
        // ２．今日の日付取得
        $today = Carbon::today();
        // １と２が同じ時（同日に出勤しようとしている場合）は、出勤ボタンが押された後（started.blade.php）へリダイレクトする
       if ($oldDay->isSameDay($today)){
            return redirect('/started')->with(compact('user'));
       }else{
            return view('index', compact('user'));
        }
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
        // 現在の年月日を取得し表示する  
        $today = Carbon::today();	
        $month = intval($today->month);	
        $day = intval($today->day);	
        $format = $today->format('Y-m-d');	
        //当日の勤怠を取得	
        $items = Attendance::GetMonthAttendance($month)->GetDayAttendance($day)->get();	
        return view('date', compact('user', 'attendances', 'items', 'day', 'format'));
    }
    public function daily(Request $request) 
    {
        $items = Attendance::
            where('year',$request->year)
            ->where('month',$request->month)
            ->where('day',$request->day)
            ->get();
        return redirect('/attendance')->with('items', $items);
    }
}

