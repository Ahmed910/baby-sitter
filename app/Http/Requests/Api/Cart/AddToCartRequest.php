<?php

namespace App\Http\Requests\Api\Cart;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends ApiMasterRequest
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
            'cart'=>'required|array',
            'cart.*.main_category_id'=>'required|exists:main_categories,id',
            'cart.*.sub_category_id'=>'nullable|exists:sub_categories,id',
            'cart.*.second_category_id'=>'nullable|exists:second_categories,id',
            'cart.*.selender_id'=>'nullable|exists:selenders,id',
            'cart.*.price'=>'required|numeric'
        ];
    }
}
