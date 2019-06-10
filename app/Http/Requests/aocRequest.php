<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class aocRequest extends FormRequest
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
        'aoc_holder'=>'required',
        'aoc_certificate_no' => 'required',
        'aoc_certificate' => 'required',
    	'issued_date' => 'required',
    	'validity' => 'required',
    	'ops_specs' => 'required',
    	'part_g' => 'required',
    	'remarks' => 'required',
        ];
    }
}
