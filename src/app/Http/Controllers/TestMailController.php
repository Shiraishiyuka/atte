<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function sendTestMail()
    {
        Mail::raw('This is a test email', function ($message) {
            $message->to('test@example.com')
            ->subject('Test Email');
        });

        return view('emails.auth_link');
    }
}
