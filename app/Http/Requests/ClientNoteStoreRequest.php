<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientNoteStoreRequest extends FormRequest
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
            'email' => 'required|email|max:254',
            'body' => 'required|between:2,255',
        ];
    }
}
