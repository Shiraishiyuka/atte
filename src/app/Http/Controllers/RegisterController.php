<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

      
        //認証情報を取得
        $credentials = $request->only('email', 'password');

        //パスワード認証
        if (Auth::validate($credentials)) {
            $user = User::where('email', $request->email)->first();



        //認証リンクをメールで送信
        Mail::to($user->email)->send(new \App\Mail\AuthLinkMail($user));


        return back()->with('message', '認証リンクがメールに送信されました。');
    } else {
            //パスワードが間違っている場合
            return back()->withErrors(['password' => 'パスワードが正しくありません']);
        }
}
}