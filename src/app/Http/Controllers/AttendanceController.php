<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WorkShift;
use App\Models\User;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function form(Request $request)
    //ユーザーの勤務状態を確認し、勤務開始や休憩の状態に応じてボタンの有効/無効を設定
    {
        $userId = Auth::id();

        // 勤務中かどうかの確認
        $workShift = WorkShift::where('user_id', Auth::id())
            ->whereNull('end_time')
            ->first();

        // 勤務が開始されているか？
        $isWorking = $workShift ? true : false;

        // 休憩中かどうかの確認
       $isOnBreak = $workShift && $workShift->break_times
            ? collect(json_decode($workShift->break_times))->whereNull('break_end')->isNotEmpty()
            : false;

        // 全てのボタンの状態を決定
         $canStartWork = !$isWorking && !$isOnBreak;
        $canEndWork = $isWorking && !$isOnBreak;
        $canStartBreak = $isWorking && !$isOnBreak;
        $canEndBreak = $isWorking && $isOnBreak;

        if ($request->has('home')) {
            return redirect('/');
        }

        if ($request->has('data')) {
            return $this->index($request);
        }

        if ($request->has('logout')) {
            Auth::logout();
            return redirect()->route('login.show');
        }

        return view('home', compact('canStartWork', 'canEndWork', 'canStartBreak', 'canEndBreak'));
    }

    //ユーザーが勤務を開始したときに、新しい勤務シフト（WorkShift）を作成
      public function startWorkShift(Request $request)
    {
        $workShift = WorkShift::create([
            'user_id' => Auth::id(),
            'start_time' => now(),
            'break_times' => json_encode([]),  // 空の配列をデフォルト値として設定
        ]);

        return redirect('/')->with('message', '勤務を開始しました');
    }

    //勤務中のユーザーが休憩を開始したときの処理
    public function startBreak(Request $request)
    {
        $workShift = WorkShift::where('user_id', Auth::id())->whereNull('end_time')->first();

        if ($workShift) {
            // 休憩の開始
            $breaks = json_decode($workShift->break_times, true);
            $breaks[] = ['break_start' => now(), 'break_end' => null];
            $workShift->update(['break_times' => json_encode($breaks)]);

            return redirect('/')->with('message', '休憩を開始しました');
        }

        return redirect('/')->with('error', '勤務が開始されていません');
    }


    //休憩終了の処理
   public function endBreak(Request $request)
    {
        $workShift = WorkShift::where('user_id', Auth::id())->whereNull('end_time')->first();

        if ($workShift) {
            $breaks = json_decode($workShift->break_times, true);
            $lastBreak = end($breaks);

            if ($lastBreak && !$lastBreak['break_end']) {
                $lastBreak['break_end'] = now();
                array_pop($breaks);
                $breaks[] = $lastBreak;

                $workShift->update(['break_times' => json_encode($breaks)]);

                return redirect('/')->with('message', '休憩を終了しました');
            }

            return redirect('/')->with('error', '現在の休憩が見つかりません');
        }

        return redirect('/')->with('error', '勤務が開始されていません');
    }


    //勤務終了の処理
    public function endWorkShift(Request $request)
    {
        $workShift = WorkShift::where('user_id', Auth::id())->whereNull('end_time')->first();

        if ($workShift) {
            $workShift->update([
                'end_time' => now(),
                'total_break_time' => $this->calculateTotalBreakTime($workShift),
            ]);

            return redirect('/')->with('message', '勤務を終了しました');
        }

        return redirect('/')->with('error', '勤務が開始されていません');
    }

//休憩時間の合計を計算
   private function calculateTotalBreakTime($workShift)
{
    // JSONデコードして配列に変換
    $breaks = $workShift->break_times ? json_decode($workShift->break_times, true) : [];

    $totalSeconds = 0;

    foreach ($breaks as $break) {
        if (isset($break['break_end'])) {
            $start = Carbon::parse($break['break_start']);
            $end = Carbon::parse($break['break_end']);
            $totalSeconds += $end->diffInSeconds($start);
        }
    }

    return gmdate('H:i:s', $totalSeconds);
}


    //指定された日付（デフォルトは今日）の勤務シフトを表示
    public function index(Request $request)
    {
        if ($request->has('home')) {
            return $this->form($request);
        }

        if ($request->has('data')) {
            return redirect('/attendance');
        }

        if ($request->has('logout')) {
            Auth::logout();
            return redirect()->route('login.show');
        }

        if ($request->has('show_users')) {
            return redirect('/users');
        }

        $date = $request->input('date', Carbon::today()->toDateString());

    // 勤務開始または終了がその日にかかる勤務シフトを取得
    $workShifts = WorkShift::where(function($query) use ($date) {
        $query->whereDate('start_time', '<=', $date) 
        ->where(function($query) use ($date) {
            $query->whereNull('end_time')  
            ->orWhereDate('end_time', '>=', $date);
        });
    })
             ->with('user')
             ->paginate(5)
             ->withQueryString();
        



    $dailyAttendances = [];

    foreach ($workShifts->items() as $workShift) {
        $dailyAttendances[] = [
            'name' => $workShift->user->name,
            'start_time' => Carbon::parse($workShift->start_time)->toDateString() == $date ? Carbon::parse($workShift->start_time)->format('H:i:s') : '-',
            'end_time' => Carbon::parse($workShift->end_time)->toDateString() == $date ? Carbon::parse($workShift->end_time)->format('H:i:s') : '-',
            'total_break_time' => $this->calculateBreakTimeForDate($workShift, $date),
            'total_work_time' => $this->calculateWorkTimeForDate($workShift->start_time, $workShift->end_time, $date),
        ];
    }

    return view('index', compact('workShifts', 'dailyAttendances', 'date'));
 }
 
//特定の日の休憩時間を計算するメソッド
private function calculateBreakTimeForDate($workShift, $date)
{
    // JSONデコードして配列に変換
    $breaks = $workShift->break_times ? json_decode($workShift->break_times, true) : [];
    $totalSeconds = 0;

    // 休憩がない場合は 00:00:00 を返す
    if (empty($breaks)) {
        return '00:00:00';
    }

     

    foreach ($breaks as $break) {
        $start = Carbon::parse($break['break_start']);
        $end = isset($break['break_end']) ? Carbon::parse($break['break_end']) : now();

        if ($start->toDateString() < $date && $end->toDateString() > $date) {
            return '24:00:00';  // The entire day is a break
        }

        // 休憩開始日と終了日が異なる場合、それぞれの時間を分割して計算
        if ($start->toDateString() == $date && $end->toDateString() != $date) {
            $totalSeconds += Carbon::parse($date)->endOfDay()->diffInSeconds($start);
        } elseif ($start->toDateString() != $date && $end->toDateString() == $date) {
            $totalSeconds += $end->diffInSeconds(Carbon::parse($date)->startOfDay());
        } elseif ($start->toDateString() == $date && $end->toDateString() == $date) {
            $totalSeconds += $end->diffInSeconds($start);
        }
    }

    return $totalSeconds > 0 ? gmdate('H:i:s', $totalSeconds) : '00:00:00';
}

//勤務開始日と終了日が異なる場合、その日の勤務時間だけを計算
private function calculateWorkTimeForDate($startTime, $endTime, $date)
{
    $start = Carbon::parse($startTime);
    $end = $endTime ? Carbon::parse($endTime) : now();
    $totalSeconds = 0;

    // 勤務開始日と終了日が異なる場合、その日の時間を分割
    if ($start->toDateString() == $date && $end->toDateString() != $date) {
        $totalSeconds = Carbon::parse($date)->endOfDay()->diffInSeconds($start);
    } elseif ($start->toDateString() != $date && $end->toDateString() == $date) {
        $totalSeconds = $end->diffInSeconds(Carbon::parse($date)->startOfDay());
    } elseif ($start->toDateString() == $date && $end->toDateString() == $date) {
        $totalSeconds = $end->diffInSeconds($start);
    }

    return $totalSeconds > 0 ? gmdate('H:i:s', $totalSeconds) : '-';
}


//各ユーザーごとの勤怠表を表示する
     // 各ユーザーごとの勤怠表を表示するメソッド
    public function showUserAttendance(User $user, Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());

        // 指定されたユーザーの勤務シフトを取得
        $workShifts = WorkShift::where('user_id', $user->id)
            ->where(function ($query) use ($date) {
                $query->whereDate('start_time', $date)
                      ->orWhereDate('end_time', $date);
            })
            ->paginate(5)
            ->withQueryString(); 

        $dailyAttendances = [];

        foreach ($workShifts as $workShift) {
            $dailyAttendances[] = [
                'start_time' => Carbon::parse($workShift->start_time)->toDateString() == $date ? Carbon::parse($workShift->start_time)->format('H:i:s') : '-',
                'end_time' => Carbon::parse($workShift->end_time)->toDateString() == $date ? Carbon::parse($workShift->end_time)->format('H:i:s') : '-',
                'total_break_time' => $this->calculateBreakTimeForDate($workShift, $date),
                'total_work_time' => $this->calculateWorkTimeForDate($workShift->start_time, $workShift->end_time, $date),
            ];
        }

        return view('users.show', compact('user', 'dailyAttendances', 'date', 'workShifts'));
    }
}