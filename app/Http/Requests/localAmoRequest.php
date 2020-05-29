<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class localAmoRequest extends FormRequest
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
            'holder_criteria' => 'required',
            'amo_approval_number' => 'required',
            'maintenance_locations' => 'required',
            'expiry' => 'required',
            'amo_pm_aprvl_pg_lep' => 'required',
        ];
    }
}
