<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\Models\LockerException;

class Locker extends Model
{
    protected $fillable = [
        'guid',
    ];

    protected $with = [
        'claims',
    ];

    public function isUnlockable()
    {
        if (!$this->activeClaim()) {
            throw new LockerException('Locker is not claimed.');
        }

        return ($this->activeClaim()->attemptsLeft() > 0);
    }

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
