<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;

class MailController extends Controller
{
    public function claim(Request $request)
    {
        // $name = 'Bram';
        // //Mail::to('walker.bram@gmail.com')->send(new TestMail($name));

        // Mail::send('emails.name', ['name' => $name], function ($message) {

        //     $message->to('walker.bram@gmail.com');
        //     $message->subject('NEW Subject!');
        // });
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
