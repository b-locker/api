<?php

namespace App\Http\Requests;

use App\Models\LockerClaim;
use Illuminate\Foundation\Http\FormRequest;

class LockerClaimUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key' => 'required|min:6|max:100',
            'setup_token' => [
                function ($attribute, $providedToken, $fail) {
                    if (!$this->isSetupTokenMatching($providedToken)) {
                        $fail('The ' . $attribute . ' is invalid.');
                    }
                },
            ],
        ];
    }

    private function isSetupTokenMatching($providedToken)
    {
        $claim = LockerClaim::findOrFail($this->claimId);
        return ($claim->setup_token === $providedToken);
    }
}
