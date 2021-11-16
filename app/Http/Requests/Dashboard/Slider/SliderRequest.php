<?php

namespace App\Http\Requests\Dashboard\Slider;

use Illuminate\Foundation\Http\FormRequest;

class SliderRequest extends FormRequest
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
        $slider = $this->slider ? $this->slider->id : null;
        $file = 'required|file|mimes:png,jpg,jpeg,gif,mp4,wmv,avi,mov|max:5124';
        if ($slider) {
         $file = 'nullable|file|mimes:png,jpg,jpeg,gif,mp4,wmv,avi,mov|max:5124';
        }
        $rules=[
            'file' => $file,
            'is_active' => 'required|in:0,1',
        ];

        return $rules;
    }


}
