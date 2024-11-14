<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class LoginController extends Controller
{


//ログイン画面の表示
    public function showLoginForm()
{
    return view('auth.login');
}

    //メール認証リンクを送信
    public function sendLoginLink(Request $request)
    {
        //バリデーション
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required',
        ]);

        //認証情報を取得
        $credentials = $request->only('email', 'password');

        //パスワード認証
        if (Auth::validate($credentials)) {
            $user = User::where('email', $request->email)->first();

             // トークンの生成と保存
            $token = Str::random(60);
            $user->auth_token = $token;
            $user->save();



        //認証リンクをメールで送信
        Mail::to($user->email)->send(new \App\Mail\AuthLinkMail($user));


        return back()->with('message', '認証リンクがメールに送信されました。');
    } else {
            //パスワードが間違っている場合
            return back()->withErrors(['password' => 'パスワードが正しくありません']);
        }
    }

    //リンククリック時に認証を確定
    public function verify($token)
    {
        $user = User::where('auth_token', $token)->firstOrFail();

        //認証が成功したら、トークンをリセットしてログイン
        $user->auth_token = null;
        $user->save();

        //認証済みとしてログイン
        Auth::login($user, true);
        

        return redirect()->route('home');

    }






    /*public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // 認証に成功した場合
            return redirect('/');
        }

        // 認証に失敗した場合
        return back()->withErrors([
            'email' => '提供された資格情報は正しくありません。',
        ]);
    }*/

    //ログアウト処理
    public function logout()
    {
        Auth::logout();

        return redirect('/login'); // ログインページにリダイレクト
    }
}
