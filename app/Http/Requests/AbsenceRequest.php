<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AbsenceRequest extends FormRequest
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
           'type' 		 	=> 'required|max:50',
           'employee_id'	=> 'required',
           'start_date' 	=> 'required',
           'end_date'   	=> 'required',
           'start_time' 	=> 'required',
           'end_time'   	=> 'required',
           'approve_reason' => 'max:255',
           'approve'    	=> 'max:10',
           'comment'    	=> 'max:500',
           
        ];
    }
}