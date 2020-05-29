<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class foreignRegistrationMarksRequest extends FormRequest
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
            'foreign_registration_marks'=>'required'
        ];
    }
}
