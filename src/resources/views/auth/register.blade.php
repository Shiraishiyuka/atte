@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/register.css') }}"/>
@endsection

@section('content')
<div class="register_heading">
    <p class="register_text">会員登録</p>
</div>

<form class="register_form" action="{{ route('register') }}" method="post">
    @csrf
    <div class="input_field">
        <input type="text" name="name" class="text" placeholder="名前" value="{{ old('name') }}">
        <div class="form_error">
            @error('name')
                {{ $message }}
            @enderror
        </div>
    </div>

    <div class="input_field">
        <input type="email" name="email" class="text" placeholder="メールアドレス" value="{{ old('email') }}">
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

    <div class="input_field">
        <input type="password" name="password_confirmation" class="text" placeholder="確認用パスワード" />
        <div class="form_error">
            @error('password_confirmation')
                {{ $message }}
            @enderror
        </div>
    </div>

    <div class="button">
        <button class="button-submit">会員登録</button>
    </div>
</form>

<div class="return_button">
    <form action="{{ route('login.show') }}" method="get">
        <div class="note">アカウントをお持ちの方はこちらから</div>
        <div class="login_button">
            <button class="login_button-submit" type="submit">ログイン</button>
        </div>
    </form>
</div>
@endsection




