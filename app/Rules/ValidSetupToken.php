<?php

namespace App\Rules;

use App\Models\LockerClaim;
use Illuminate\Contracts\Validation\Rule;

class ValidSetupToken implements Rule
{
    private $lockerClaim;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $claimId)
    {
        $this->lockerClaim = LockerClaim::findOrFail($claimId);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {

        return ($this->lockerClaim->setup_token === $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The setup token is invalid.';
    }
}
