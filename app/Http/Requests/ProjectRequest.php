<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name'  =>'required|max:191',
            'duration'  =>'required',
            'project_no'  =>'required',
            'day_hours'  =>'required',
            'start_date'  =>'required',
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
            'name.required' => 'Unos naziva projekta je obavezan',
            'name.max'      => 'Dozvoljen unos max :max znakova',
			'duration.required'  => 'Unos trajanja je obavezan',
			'project_no.required'  => 'Unos broja projekta je obavezan',
			'project_no'     => 'Dozvoljen je unos samo brojeva',
			'day_hours.required'  => 'Unos radnih sati u danu je obavezan',
			'start_date.required'  => 'Unos planiranog datuma početka je obavezan'        
		];
	}
}
