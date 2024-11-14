<?php

namespace Database\Factories;
use App\Models\User;
use App\Models\WorkShift;
use Carbon\Carbon;


use Illuminate\Database\Eloquent\Factories\Factory;

class WorkShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 勤務開始時間を9月18日から25日までのランダムな時間に設定
        $start_time = Carbon::create(2024, 9, rand(18, 25), rand(0, 23), rand(0, 59), rand(0, 59));

        // 勤務終了時間をランダムに設定
        $end_time = (clone $start_time)->addHours(rand(5, 8))->addMinutes(rand(0, 59));

        // 休憩時間をランダムに設定
        $break_start = (clone $start_time)->addHours(rand(2, 4));
        $break_end = (clone $break_start)->addMinutes(rand(15, 45));

        // 休憩時間の配列を作成
        $break_times = [
            [
                'break_start' => $break_start->format('Y-m-d H:i:s'),
                'break_end' => $break_end->format('Y-m-d H:i:s')
            ]
        ];

        // 総休憩時間をランダムに生成
        $total_break_time = sprintf('%02d:%02d:00', rand(0, 2), rand(0, 59));

        return [
            'user_id' => User::factory(),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'break_times' => json_encode($break_times), // JSON形式で保存
            'total_break_time' => $total_break_time,
        ];
    }
    }

