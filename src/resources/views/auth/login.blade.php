@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/login.css') }}"/>
@endsection

@section('content')
<div class="login_heading">
<p class="login_text">ログイン</p>
</div>

<form class="login_form" action="{{ route('login.email.auth') }}" method="post">
    @csrf
<div class="input_field">
        <input type="email" name="email" class="text" placeholder="メールアドレス" value="{{ old('email') }}" />
        <div class="form_error">
            @error('email')
            {{ $message }}
            @enderror
        </div>
        </div>

        <div class="input_field">
        <input type="password" name="password" class="text" placeholder="パスワード" />
        <div class="form_error">
            @error('password')
            {{ $message }}
            @enderror
        </div>
        </div>

        <div class="button">
            <button class="button-submit">ログイン</button>
        </div>
        </form>

        <div class="register_button">
            <form action="{{ route('register.show') }}" method="get">

        <div class="note">アカウントをお持ちでない方はこちらから</div>

        <div class="register_button">
        <button class="register_button-submit" type="submit">会員登録</button>
        </div>
        </form>

        </div>

        @endsection('content')