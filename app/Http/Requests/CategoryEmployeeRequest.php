<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryEmployeeRequest extends FormRequest
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
            'mark'          =>'required|max:2',
            'description'  =>'required|max:255'
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
			'mark.required'         => 'Unos oznake je obavezan',
			'description.required'  => 'Unos opisa je obavezan',
            'mark.max'              => 'Dozvoljen unos max :max znakova',
			'description.max'       => 'Dozvoljen unos max :max znakova' 
		];
	}
}
