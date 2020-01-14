<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class LockerClaim extends Model
{
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
    public function isCurrentlyInEffect()
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
