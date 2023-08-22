<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use App\Http\Controllers\AttendanceController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', [RegisteredUserController::class,'create']);


// 以下ログイン時のみ有効な動作
Route::middleware('auth')->group(function () {
// 打刻ページ表示
Route::get('/', [AttendanceController::class,'index']);
// dateページ表示
Route::get('/attendance', [AttendanceController::class,'confirm']);
// 勤務開始ボタン選択後処理
Route::post('/timein', [AttendanceController::class,'punchIn']);
// 勤務終了ボタン選択時処理
Route::post('/timeout', [AttendanceController::class,'punchOut']);
// 休憩開始ボタン選択
Route::post('/breakin', [AttendanceController::class,'breakIn']);
// 休憩終了ボタン
Route::post('/breakout', [AttendanceController::class,'breakOut']);
});