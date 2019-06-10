<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class aircraftRequest extends FormRequest
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
            'aircraft_maker_id'=>'required',
            'registration_marks' => 'required',
            'aircraft_type'=>'required',
            'aircraft_serial_number'=>'required',
            'year_of_manufacture'=>'required',
            'registration_date'=>'required',
            'registered_owner'=>'required',
            'weight'=>'required'
        ];
    }
}
