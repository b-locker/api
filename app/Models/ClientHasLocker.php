<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientHasLocker extends Model
{
    protected $fillable = [
        'client_id',
        'locker_id',
        'key_hash',
    ];

    public function lockers()
    {
        return $this->hasOne(Locker::class);
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }
}
