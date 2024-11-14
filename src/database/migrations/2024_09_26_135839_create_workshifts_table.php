<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkshiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workshifts', function (Blueprint $table) {
          $table->id();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('start_time')->nullable();   // 勤務開始時間
            $table->timestamp('end_time')->nullable();     // 勤務終了時間

            // 休憩時間をJSON形式で管理
            $table->json('break_times');       // 休憩開始と終了の配列

            // 総休憩時間
            $table->time('total_break_time')->default('00:00:00');

            // 勤務シフトの作成/更新時間
            $table->timestamps();

            // 外部キー (ユーザーが削除されたらカスケード削除)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workshifts');
    }
}
