<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LockerClaim extends Model
{
    protected $fillable = [
        'client_id',
        'locker_id',
        'key_hash',
    ];

    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
