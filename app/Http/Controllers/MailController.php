<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function claim(Request $request)
    {
        // Local id and token generation for now.
        $email = $request->input('email');
        $lockerGuid = Str::random(8);
        $token = Str::random(16);
        $url = config('app.url').'/claim/passcode?id=' . e($lockerGuid) . '&token=' . e($token);

        Mail::send('emails.claim', ['lockerGuid' => $lockerGuid, 'url' => $url], function ($message) use($email) {
            $message->to($email);
            $message->subject('Set passcode');
        });

        return 'claim sent to '.$email;
    }

    public function forgot(Request $request)
    {
        // Local id and token generation for now.
        $email = $request->input('email');
        $lockerGuid = Str::random(8);

        Mail::send('emails.forgot', ['lockerGuid' => $lockerGuid], function ($message) use ($email) {
            $message->to($email);
            $message->subject('New locker passcode');
        });

        return 'forgot sent to ' . $email;
    }

    public function end(Request $request)
    {
        // Local id and token generation for now.
        $email = $request->input('email');
        $lockerGuid = Str::random(8);

        Mail::send('emails.end', ['lockerGuid' => $lockerGuid], function ($message) use ($email) {

            $message->to($email);
            $message->subject('Ownership ended');
        });

        return 'end sent to ' . $email;
    }

    public function lockdown(Request $request)
    {
        // Local id and token generation for now.
        $email = $request->input('email');
        $lockerGuid = Str::random(8);
        $token = Str::random(16);
        $url = config('app.url').'/lockdown?id=' . e($lockerGuid) . '&token=' . e($token);

        Mail::send('emails.lockdown', ['lockerGuid' => $lockerGuid, 'url' => $url], function ($message) use ($email) {

            $message->to($email);
            $message->subject('Someone tried to access your locker');
        });

        return 'lockdown sent to ' . $email;
    }
}
