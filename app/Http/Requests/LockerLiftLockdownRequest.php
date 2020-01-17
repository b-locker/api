<?php

namespace App\Http\Requests;

use App\Rules\ValidSetupToken;
use Illuminate\Foundation\Http\FormRequest;

class LockerLiftLockdownRequest extends FormRequest
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
            'setup_token' => [
                'required',
                new ValidSetupToken($this->claimId),
            ],
        ];
    }
}
