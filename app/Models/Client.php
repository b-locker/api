<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'email',
    ];

    public function lockerClaims()
    {
        return $this->hasMany(LockerClaim::class);
    }

    public function notes()
    {
        return $this->hasMany(ClientNote::class);
    }
}
