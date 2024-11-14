<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index_user(Request $request) {

    if ($request->has('back')) {
            return redirect()->route('users.index');
        }

        $users = User::paginate(5);
        return view('users.index', compact('users'));
    }
}
