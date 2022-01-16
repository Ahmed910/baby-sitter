<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SitterWorkerRequest extends FormRequest
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
            'name'=>'required|string|between:2,200',

            'max_num_of_child_care'=>'required|integer',
            'level_experience'=>'required|in:entry_level,intermediate,mid_level,senior',
            'level_percentage'=>'required|gte:0|lte:100',
            'total_num_of_student'=>'required|integer',
            'center_id'=>'required|exists:users,id,deleted_at,NULL,user_type,childcenter',
            'image'=>'nullable|image|mimes:jpg,jpeg,gif,png'
        ];
    }
}
