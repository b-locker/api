<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    protected $fillable = [
        'guid',
    ];

    public function claims()
    {
        return $this->hasMany(LockerClaim::class);
    }
}
