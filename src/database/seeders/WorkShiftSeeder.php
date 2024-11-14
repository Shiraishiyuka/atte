<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WorkShift;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

      // カスタム勤務シフトのダミーデータを作成するメソッド
    public function customShift($start_time, $end_time, $break_times, $total_break_time)
    {
        return $this->state(function () use ($start_time, $end_time, $break_times, $total_break_time) {
            return [
                'start_time' => $start_time,
                'end_time' => $end_time,
                'break_times' => json_encode($break_times),
                'total_break_time' => $total_break_time,
            ];
        });
    }


       public function run()
    {
        // ユーザーを作成
        $user1 = User::create(['name' => '岩本由美', 'email' => 'iwamoto1@example.com', 'password' => bcrypt('wer')]);
        $user2 = User::create(['name' => '木村菜摘', 'email' => 'kimura@example.com', 'password' => bcrypt('sdf')]);
        $user3 = User::create(['name' => '伊藤瑠璃', 'email' => 'itou@example.com', 'password' => bcrypt('xcv')]);

        // 1名目: 15日12時勤務開始、18時に勤務終了
        WorkShift::create([
            'user_id' => $user1->id,
            'start_time' => Carbon::create(2024, 10, 15, 12, 0, 0),
            'end_time' => Carbon::create(2024, 10, 15, 18, 0, 0),
            'break_times' => json_encode([]),
            'total_break_time' => '00:00:00'
        ]);

        // 2名目: 15日12時勤務開始、15日20時に休憩開始、16日2時に休憩終了、26日4時に勤務終了
        WorkShift::create([
            'user_id' => $user2->id,
            'start_time' => Carbon::create(2024, 10, 15, 12, 0, 0),
            'end_time' => Carbon::create(2024, 10, 16, 4, 0, 0),
            'break_times' => json_encode([
                ['break_start' => Carbon::create(2024, 10, 15, 20, 0, 0)->format('Y-m-d H:i:s'),
                 'break_end' => Carbon::create(2024, 10, 16, 2, 0, 0)->format('Y-m-d H:i:s')],
            ]),
            'total_break_time' => '06:00:00'
        ]);

        // 3名目: 15日12時勤務開始、15日20時に休憩開始、17日9時に休憩終了、17日10時に勤務終了
        WorkShift::create([
            'user_id' => $user3->id,
            'start_time' => Carbon::create(2024, 10, 15, 12, 0, 0),
            'end_time' => Carbon::create(2024, 10, 17, 10, 0, 0),
            'break_times' => json_encode([
                ['break_start' => Carbon::create(2024, 10, 15, 20, 0, 0)->format('Y-m-d H:i:s'),
                 'break_end' => Carbon::create(2024, 10, 17, 9, 0, 0)->format('Y-m-d H:i:s')],
            ]),
            'total_break_time' => '31:00:00'
        ]);

        WorkShift::factory()->count(30)->create();
    }
}

