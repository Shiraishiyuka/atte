@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/home.css') }}"/>
@endsection

@section('contact')
<div class="form">
    <form action="/" method="post">
        @csrf
        <input class="form_item" type="submit" value="ホーム" name="home">
        <input class="form_item" type="submit" value="日付一覧" name="data">  <!-- This will redirect to index -->
        <input class="form_item" type="submit" value="ログアウト" name="logout">
    </form>
</div>
@endsection

@section('content')
<div class="user_message">
    <p class="message">{{ Auth::user()->name }}さんお疲れ様です！</p>
    </div>

<div class="note">
<div class="grid__parent">
    <div class="box1">
        <form action="/attendance/start" method="post">
            @csrf
            <!--勤務中なら無効化-->
            <button class="text" {{ $canStartWork ? '' : 'disabled' }}
            >勤務開始</button>
        </form>
    </div>

    <div class="box2"></div>
    <div class="box3">
        <form action="{{ route('attendance.end') }}" method="post">
            @csrf
            <!-- 勤務中でないなら無効化 -->
            <button class="text" {{ $canEndWork ? '' : 'disabled' }}>勤務終了</button>
        </form>
    </div>
    <div class="box4"></div>
    <div class="box5">
        <form action="{{ route('attendance.break.start') }}" method="post">
            @csrf
            <!-- 勤務していないか、休憩中なら無効化 -->
            <button class="text"  {{ $canStartBreak ? '' : 'disabled' }}>休憩開始</button>
        </form>
    </div>
    <div class="box6">
        <form action="{{ route('attendance.break.end') }}" method="post">
            @csrf
            <!-- 休憩中でないなら無効化 -->
            <button class="text" {{ $canEndBreak ? '' : 'disabled' }}>休憩終了</button>
        </form>
    </div>
</div>
</div>
@endsection