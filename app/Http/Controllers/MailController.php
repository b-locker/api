<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class MailController extends Controller
{
    public function claim(Request $request)
    {
        $email = $request->input('email');
        // //Mail::to('walker.bram@gmail.com')->send(new TestMail($name));

        Mail::send('emails.name', ['name' => 'Testing', 'email' => $email], function ($message) use($email) {

            $message->to($email);
            $message->subject('Claim locker.');
        });

        return 'claim';
    }

    public function forgot(Request $request)
    {
        return 'forgot';
    }

    public function end(Request $request)
    {
        return 'end';
    }

    public function lockdown(Request $request)
    {
        return 'lockdown';
    }
}
