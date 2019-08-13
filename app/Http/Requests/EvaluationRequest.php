<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
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
            'employee_id'	=> 'required',
			'date'			=> 'required',
			'rating'		=> 'required',
			'question_id' 	=> 'required',
			'category_id' 	=> 'required',
			'koef' 			=> 'required'
        ];
    }
}
