@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/users_show.css') }}"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endsection


@section('content')

<!--日付選択-->
<div class="su">
    <div class="box">
    <a href="?date={{ \Carbon\Carbon::parse($date)->subDay()->toDateString() }}" class="date_arrow"><</a>
    </div>
    <span class="year">{{ $date }}</span>
     <div class="box">
        <a href="?date={{ \Carbon\Carbon::parse($date)->addDay()->toDateString() }}" class="date_arrow" >></a>
    </div>
</div>

    <!-- 勤怠表 -->
    <table class="index_table">

            <tr class="index_row">
                <th>勤務開始</th>
                <th>勤務終了</th>
                <th>休憩時間</th>
                <th>勤務時間</th>
            </tr>

            @foreach ($dailyAttendances as $attendance)
            <tr>
                <td>{{ $attendance['start_time'] }}</td>
                <td>{{ $attendance['end_time'] }}</td>
                <td>{{ $attendance['total_break_time'] }}</td>
                <td>{{ $attendance['total_work_time'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

 <!-- ページネーションリンク -->
<div class="pagination">
{{ $workShifts->links() }}
</div>



    <a href="{{ route('users.index') }}" class="back">管理表へ戻る</a>
@endsection