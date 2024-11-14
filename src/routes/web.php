<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\TestMailController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\UserController;




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






Route::match(['get', 'post'], '/attendance', [AttendanceController::class, 'index'])->name('index_route');

Route::group(['middleware' => ['auth']], function () {
    Route::match(['get', 'post'], '/', [AttendanceController::class, 'form'])->name('home');
});

/*勤怠管理しいステムのルート*/
Route::post('/attendance/start', [AttendanceController::class, 'startWorkShift'])->name('attendance.start');
Route::post('attendance/end', [AttendanceController::class, 'endWorkShift'])->name('attendance.end');
Route::post('/attendance/break/start', [AttendanceController::class, 'startBreak'])->name('attendance.break.start');
Route::post('/attendance/break/end', [AttendanceController::class, 'endBreak'])->name('attendance.break.end');

Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register.show');
Route::post('/register', [RegisterController::class, 'register'])->name('register');


Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.show');
Route::post('/login/email-auth', [LoginController::class, 'sendLoginLink'])->name('login.email.auth');
Route::get('login/verify/{token}', [LoginController::class, 'verify'])->name('login.verify');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');



//二段階認証の際のルート（標準搭載されている）
Route::post('/login', [LoginController::class, 'login'])->name('login');
// 認証メール送信ルート（ユーザーが登録後に呼ばれる）
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// 認証リンクのクリックを処理するルート
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();  // 認証を完了する
    return redirect('/home');  // 認証後にリダイレクト
})->middleware(['auth', 'signed'])->name('verification.verify');

// 再送信リクエストの処理
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', '認証リンクを再送信しました');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


//メールでの認証機能の追加
Route::get('/send-test-email', [TestMailController::class, 'sendTestMail']);


//ユーザー一覧ページと各ユーザーごとの勤怠管理表
Route::get('/users', [UserController::class, 'index_user'])->name('users.index');

//ユーザーごとの勤怠表の表示ページ
Route::get('/users/{user}/attendance', [AttendanceController::class, 'showUserAttendance'])->name('users.attendance');