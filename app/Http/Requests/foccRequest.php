<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class foccRequest extends FormRequest
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
            'aoc_holder_id'=>'required',
            'focc_no' => 'required',
            'aircraft_type' => 'required',
            'aircraft_reg_no' => 'required',
            'type_of_operations' => 'required',
            'date_of_first_issue' => 'required'
        ];
    }
}
