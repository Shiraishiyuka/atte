@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/users_index.css') }}"/>
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
    <table class="user_table">

    <tr class="index_row">
        <td class="index_label">名前</td>
        <td class="index_label">メールアドレス</td>
        <td class="index_label">各勤務管理表</td>
        </tr>
        @foreach ($users as $user)
    <tr class="index_row">
        <td class="index_date">{{ $user->name }}</td>
        <td class="index_date">{{ $user->email }}</td>
        <td class="index_date"><a href="{{ route('users.attendance', $user->id) }}" class="btn btn-primary">勤怠表を見る</a></td>
        </tr>
        @endforeach

         <!-- 空行を追加して5行に満たない場合を埋める -->
        @for ($i = 0; $i < (5 - count($users)); $i++)
            <tr class="index_row">
                <td class="index_date">&nbsp;</td>
                <td class="index_date">&nbsp;</td>
                <td class="index_date">&nbsp;</td>
            </tr>
        @endfor
    </table>

<div class="pagination">
 <!-- ページネーション -->
    {{ $users->links() }}
    </div>
@endsection