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
            'user_id' 			=> 'required',
            'oib' 				=> 'required|max:20',
            'oi' 				=> 'required|max:20',
            'oi_expiry' 		=> 'required',
            'b_day' 			=> 'required',
            'email' 			=> 'required|email|max:50',
            'title' 			=> 'required|max:150',
            'qualifications' 	=> 'required|max:20',
            'work_id' 			=> 'required',
            'reg_date' 			=> 'required',
            'probation' 		=> 'integer',
        ];
    }
}