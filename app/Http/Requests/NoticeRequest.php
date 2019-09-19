<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoticeRequest extends FormRequest
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
            'to_department'	=> 'required|max:100',
            'employee_id'	=> 'required|max:100',
            'title'		    => 'required|max:100',
            'notice'	    => 'required|max:16777215',
        ];
    }
}
