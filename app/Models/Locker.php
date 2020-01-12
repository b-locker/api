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
        $foreignKey = 'locker_id';
        return $this->hasMany(LockerClaim::class, $foreignKey);
    }
}
