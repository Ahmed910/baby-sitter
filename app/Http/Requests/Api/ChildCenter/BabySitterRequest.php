<?php

namespace App\Http\Requests\Api\ChildCenter;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class BabySitterRequest extends ApiMasterRequest
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
            'bio'=>'nullable',
            'max_num_of_child_care'=>'required|integer',
            'level_experience'=>'required|in:entry_level,intermediate,mid_level,senior',
            'level_percentage'=>'required|gte:0|lte:100',
            'total_num_of_student'=>'required|integer',
            'image'=>'nullable|image|mimes:jpg,jpeg,gif,png'
        ];
    }
}
