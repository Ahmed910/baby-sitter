<?php

namespace App\Http\Requests\Dashboard\OfferRequest;

use Illuminate\Foundation\Http\FormRequest;

class changeStatusRequest extends FormRequest
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
        // dd('aaaa');
        return [
            'status'=>'required|in:accepted,rejected',
            'reject_reason'=>'nullable|required_if:status,rejected'
        ];
    }
}
