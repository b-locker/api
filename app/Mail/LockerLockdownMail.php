<?php

namespace App\Mail;

use Carbon\Carbon;
use App\Models\LockerClaim;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class LockerLockdownMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $subject;
    public $lockerClaim;
    public $setupUrl;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LockerClaim $lockerClaim)
    {
        $this->title = 'Your locker is in lockdown';
        $this->subject = $this->title .' - ' . Carbon::now()->addHour()->format('H:i') . ' - ' . config('app.name');

        $this->lockerClaim = $lockerClaim;

        $this->setupUrl = sprintf(
            '%s/liftlockdown/passcode?locker_guid=%s&claim_id=%s&token=%s',
            rtrim(config('app.url'), '/'),
            e($lockerClaim->locker->guid),
            e($lockerClaim->id),
            e($lockerClaim->setup_token)
        );
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
            ->view('emails/lockdown')
        ;
    }
}
