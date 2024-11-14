@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}"/>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endsection

@section('contact')
<div class="form">
    <form action="/attendance" method="post">
        @csrf
        <input class="form_item" type="submit" value="ホーム" name="home">
        <input class="form_item" type="submit" value="日付一覧" name="data">  <!-- This will redirect to index -->
        <input class="form_item" type="submit" value="ログアウト" name="logout">
        <input type="submit" class="form_item" value="管理表" name="show_users">
    </form>
</div>
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

<table class="index_table">
    
    <tr class="index_row">
        <td class="index_label">名前</td>
        <td class="index_label">勤務開始</td>
        <td class="index_label">勤務終了</td>
        <td class="index_label">休憩時間</td>
        <td class="index_label">勤務時間</td>
    </tr>

<!--@if (count($dailyAttendances) > 0)-->
    @foreach ($dailyAttendances as $workShift)
    <tr class="index_row">
        <td class="index_date">{{ $workShift['name'] }}</td>
        <td class="index_date">{{ $workShift['start_time'] }}</td>
        <td class="index_date">{{ $workShift['end_time'] }}</td>
        <td class="index_date">{{ $workShift['total_break_time'] }}</td>
        <td class="index_date">{{ $workShift['total_work_time'] }}</td>
    </tr>
    @endforeach

   <!-- @else
    <tr>
        <td colspan="5">No attendance records found for this date.</td>
    </tr>
@endif-->
</table>

<!-- ページネーションリンク -->
<div class="pagination">
{{ $workShifts->links() }}
</div>
@endsection