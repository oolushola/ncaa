<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class foreignAmoRequest extends FormRequest
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
            'amo_holder' => 'required',
            'regional_country_id' => 'required',
            'amo_number' => 'required',
            'expiry' => 'required',
        ];
    }
}
