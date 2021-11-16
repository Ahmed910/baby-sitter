<?php

namespace App\Http\Requests\Api\Contact;

use App\Http\Requests\Api\ApiMasterRequest;

class ContactRequest extends ApiMasterRequest
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
       
        $fullname = 'required|string|between:2,250';
        $phone = 'required|numeric|digits_between:5,20';

        return [

           'fullname' => $fullname,
           'phone' => $phone,

           'title'    => 'nullable|string|between:2,250',

           'content'    => 'required|string|between:2,10000',
        ];
    }
}
