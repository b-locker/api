<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LockerClaim extends Model
{
    /**
     * The amount of consecutive authentication fails a client can make.
     */
    const MAX_FAILED_AUTH_ATTEMPTS = 5;

    protected $fillable = [
        'client_id',
        'locker_id',
        'setup_token',
        'start_at',
        'end_at',
    ];

    protected $dates = [
        'start_at',
        'end_at',
    ];

    public function attemptsLeft()
    {
        return static::MAX_FAILED_AUTH_ATTEMPTS - $this->failed_attempts;
    }

    /**
     * Whether the locker key is set or not.
     *
     * @return boolean
     */
    public function isSetUp()
    {
        return (isset($this->key_hash));
    }

    /**
     * Whether the locker claim is currently in effect or not.
     *
     * @return boolean
     */
    public function isActive()
    {
        if (!$this->isSetUp()) {
            return false;
        }

        if (!$this->hasTimeStarted()) {
            return false;
        }

        if ($this->hasTimeEnded()) {
            return false;
        }

        return true;
    }

    private function hasTimeStarted()
    {
        return (Carbon::now() >= $this->start_at);
    }

    private function hasTimeEnded()
    {
        return (Carbon::now() > $this->end_at);
    }

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
