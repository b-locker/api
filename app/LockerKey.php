<?php

namespace App;

use App\Models\LockerClaim;
use Illuminate\Support\Str;
use App\Mail\LockerLockdownMail;
use Illuminate\Support\Facades\Mail;
use App\Exceptions\LockerKeyException;

class LockerKey
{
    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function attempt(?LockerClaim $lockerClaim)
    {
        if (empty($lockerClaim)) {
            throw new LockerKeyException('The locker is not claimed.');
        }

        if (!$lockerClaim->locker->isUnlockable()) {
            throw new LockerKeyException('The locker is not unlockable. It could be locked down due to too many failed attempts.');
        }

        if ($this->check($lockerClaim->key_hash)) {
            $lockerClaim->failed_attempts = 0;
            $lockerClaim->save();

            return true;
        }

        $lockerClaim->failed_attempts++;
        $lockerClaim->save();

        $attemptsLeft = $lockerClaim->attemptsLeft();
        $message = 'The provided key does not work.';

        if ($attemptsLeft > 0) {
            $message .= ' You have ' . $attemptsLeft . ' attempt(s) left.';
        } else {
            $message .= ' You have no more attempts left.';

            $lockerClaim->setup_token = Str::random();
            $lockerClaim->save();

            $client = $lockerClaim->client;
            $mail = new LockerLockdownMail($lockerClaim);
            Mail::to($client->email)->send($mail);
        }

        throw new LockerKeyException($message);
    }

    private function check($storedKeyHash)
    {
        return (password_verify($this->key, $storedKeyHash));
    }
}
