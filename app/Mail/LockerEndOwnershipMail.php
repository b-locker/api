<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\LockerClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LockerEndOwnershipMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $subject;
    public $lockerClaim;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LockerClaim $lockerClaim)
    {
        $this->title = 'Locker ownership ended';
        $this->subject = $this->title .' - ' . Carbon::now()->addHour()->format('H:i') . ' - ' . config('app.name');

        $this->lockerClaim = $lockerClaim;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails/end')
        ;
    }
}
