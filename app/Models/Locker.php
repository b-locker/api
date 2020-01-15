<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    protected $fillable = [
        'guid',
    ];

    public function isCurrentlyClaimable()
    {
        // TODO: Check for start and end date if lockers can be reserved
        return (empty($this->activeClaim()));
    }

    public function activeClaim()
    {
        foreach ($this->claims as $claim) {
            if ($claim->isActive()) {
                return $claim;
            }
        }
    }

    public function claims()
    {
        $foreignKey = 'locker_id';
        return $this->hasMany(LockerClaim::class, $foreignKey);
    }
}
