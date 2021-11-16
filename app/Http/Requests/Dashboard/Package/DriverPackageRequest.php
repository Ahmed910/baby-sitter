<?php

namespace App\Http\Requests\Dashboard\Package;

use Illuminate\Foundation\Http\FormRequest;

class DriverPackageRequest extends FormRequest
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
            'package_type' => 'required|in:free,fixed,percent',
            'is_initial_package' => 'required|in:0,1',
            'is_active' => 'nullable|in:0,1',
            // For Free
            'initial_duration' => 'nullable|required_if:package_type,free|integer|gt:0',
            'number_of_orders_for_free' => 'nullable|required_if:package_type,free|integer|gte:0',
            'index_of_free_order_for_client' => 'nullable|required_if:package_type,free|integer|gte:0',
            'number_of_free_orders_for_client' => 'nullable|required_if:package_type,free|integer|gte:0',
            // For Fixed
            'package_price' => 'nullable|required_if:package_type,fixed|numeric|gt:0',
            // For Percent
            'package_percent' => 'nullable|required_if:package_type,percent|numeric|between:1,100',
            // For Discount
            'is_discount_active' => 'nullable|in:0,1',
            'discount_percent'=>'nullable|numeric|gte:0',
            'start_discount_at'=>'nullable|date|after_or_equal:'.date("Y-m-d"),
            'end_discount_at'=> 'nullable|date|after:start_discount_at',
            // For Extend
            'is_extend_active' => 'nullable|in:0,1',
            'extend_duration' => 'nullable|numeric|gte:0',
            'start_extend_at' => 'nullable|required_with:extend_duration|date|after_or_equal:'.date("Y-m-d"),
            'end_extend_at' => 'nullable|required_with:extend_duration|date|after:start_extend_at',
        ];

    }
    public function getValidatorInstance()
    {
        $data = $this->all();
        if (isset($data['start_extend_at']) && $data['start_extend_at'] != null) {
            $data['start_extend_at'] = \Carbon\Carbon::parse($data['start_extend_at'])->format("Y-m-d");
        }
        if (isset($data['end_extend_at']) && $data['end_extend_at'] != null) {
            $data['end_extend_at'] =  \Carbon\Carbon::parse($data['end_extend_at'])->format("Y-m-d");
        }
        if (isset($data['start_discount_at']) && $data['start_discount_at'] != null) {
            $data['start_discount_at'] =  \Carbon\Carbon::parse($data['start_discount_at'])->format("Y-m-d");

        }  if (isset($data['end_discount_at']) && $data['end_discount_at'] != null) {
        $data['end_discount_at'] =  \Carbon\Carbon::parse($data['end_discount_at'])->format("Y-m-d");
    }
        $this->getInputSource()->replace($data);
        return parent::getValidatorInstance();
    }

}
