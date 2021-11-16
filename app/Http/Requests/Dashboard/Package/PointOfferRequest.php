<?php

namespace App\Http\Requests\Dashboard\Package;

use Illuminate\Foundation\Http\FormRequest;

class PointOfferRequest extends FormRequest
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
            'start_at' => 'required|date|after_or_equal:'.date("Y-m-d"),
            'end_at' => 'required|date|after:start_at',
            'points' => 'required|integer|gt:0',
            'number_of_orders' => 'required|integer|gt:0',
            'fcm_notification' => 'nullable|string|between:3,500',
            'is_active' => 'nullable|in:0,1',
            'user_type' => 'required|in:client,driver,client_and_driver'
        ];
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        if (isset($data['start_at']) && $data['start_at'] != null) {
            $data['start_at'] = date('Y-m-d', strtotime($data['start_at']));
        }
        if (isset($data['end_at']) && $data['end_at'] != null) {
            $data['end_at'] = date('Y-m-d', strtotime($data['end_at']));
        }
        $this->getInputSource()->replace($data);
        return parent::getValidatorInstance();
    }
}
