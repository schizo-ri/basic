<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest
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
            'first_name'  =>'required|max:50',
			'last_name'   =>'required|max:50',
        ];
    }

    	/**
	 * Get the error messages for the defined validation rules.
	 *
	 * @return array
	 */
	public function messages()
	{
		return [
			'first_name.required' => 'Unos imena je obavezan',
			'last_name.required'  => 'Unos prezimena je obavezan',
            'first_name.max'      => 'Dozvoljen unos max :max znakova',
			'last_name.max'       => 'Dozvoljen unos max :max znakova', 
		];
	}
}
