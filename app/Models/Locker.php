<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    protected $fillable = [
        'guid',
    ];

    public function isCurrentlyAvailable()
    {
        if ($this->claims->count() === 0) {
            return true;
        }

        foreach ($this->claims as $claim) {
            if ($claim->isCurrentlyInEffect()) {
                return false;
            }
        }

        return true;
    }

    public function claims()
    {
        $foreignKey = 'locker_id';
        return $this->hasMany(LockerClaim::class, $foreignKey);
    }
}
