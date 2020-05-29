<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class foccAndMccRequest extends FormRequest
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
            'operator' => 'required',
            'operator' => 'string | required',
            'focc_no'=> 'string | required',
            'mcc_no'=> 'string | required',
            'state_of_registry_id'=> 'integer | required',
            'registered_owner'=> 'string | required',
            'aircraft_maker_id'=> 'integer | required',
            'aircraft_type_id'=> 'integer | required',
            'aircraft_reg_no_id'=> 'integer | required',
            'registered_owner'=> 'string | required',
            'date_of_first_issue' => 'string | required',
            'amo_holder_status' => 'required',
        ];
    }
}
