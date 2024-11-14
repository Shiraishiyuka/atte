<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\BreakTime;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AutoAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:attendance {action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically handle attendance and break actions.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function handle()
    {
        $action = $this->argument('action');
        $userId = 1; // テスト用に適宜ユーザーIDを固定化可能  野村健一

        if ($action === 'start') {
            Attendance::create([
                'user_id' => $userId,
                'start_time' => now(),
            ]);
            $this->info('勤務を開始しました');
        }

        if ($action === 'breakStart') {
            $attendance = Attendance::where('user_id', $userId)
                ->whereNull('end_time')
                ->first();

            if ($attendance) {
                BreakTime::create([
                    'attendance_id' => $attendance->id,
                    'break_start' => now(),
                ]);
                $this->info('休憩を開始しました');
            }
        }

        if ($action === 'breakEnd') {
            $attendance = Attendance::where('user_id', $userId)
                ->whereNull('end_time')
                ->first();

            if ($attendance) {
                $break = BreakTime::where('attendance_id', $attendance->id)
                    ->whereNull('break_end')
                    ->first();

                if ($break) {
                    $break->update(['break_end' => now()]);
                    $this->info('休憩を終了しました');
                }
            }
        }

        if ($action === 'end') {
            $attendance = Attendance::where('user_id', $userId)
                ->whereNull('end_time')
                ->first();

            if ($attendance) {
                $attendance->update([
                    'end_time' => now(),
                    'total_break_time' => $this->calculateTotalBreakTime($attendance->id),
                ]);
                $this->info('勤務を終了しました');
            }
        }
    }

    private function calculateTotalBreakTime($attendanceId)
    {
        $breaks = BreakTime::where('attendance_id', $attendanceId)->get();
        $totalSeconds = 0;

        foreach ($breaks as $break) {
            if ($break->break_end) {
                $start = Carbon::parse($break->break_start);
                $end = Carbon::parse($break->break_end);
                $totalSeconds += $end->diffInSeconds($start);
            }
        }

        return gmdate('H:i:s', $totalSeconds);
    }
    
}
