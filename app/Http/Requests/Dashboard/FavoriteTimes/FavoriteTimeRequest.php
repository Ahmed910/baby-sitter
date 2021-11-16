<?php

namespace App\Http\Requests\Dashboard\FavoriteTimes;

use Illuminate\Foundation\Http\FormRequest;

class FavoriteTimeRequest extends FormRequest
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
            'time'=>'required',
            'district_id'=>'required|exists:districts,id',
            'available_day_id'=>'required|exists:available_days,id'
        ];
    }


}
