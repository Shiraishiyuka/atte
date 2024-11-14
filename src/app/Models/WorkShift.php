<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    protected $table = 'workshifts';
    use HasFactory;
      protected $fillable = ['user_id', 'start_time', 'end_time', 'break_times', 'total_break_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 休憩時間をJSONで管理
    public function getBreakTimesAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setBreakTimesAttribute($value)
    {
        $this->attributes['break_times'] = json_encode($value);
    }
}







/* 
workshiftsテーブル=ユーザーの勤怠シフトを管理するためのテーブル
user_id: 勤務シフトがどのユーザーに紐付いているかを示す外部キー。
start_time: 勤務開始時間。
end_time: 勤務終了時間。
break_times: 休憩時間をJSONで保存するカラム。
total_break_time: その勤務シフトにおける休憩時間の合計（H:i:s形式で保存）。
外部キー制約: ユーザーが削除された場合、そのユーザーに紐付く勤務シフトも削除される（カスケード削除）。

*/