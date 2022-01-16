<?php

namespace App\Http\Requests\Dashboard\Sitter;

use Illuminate\Foundation\Http\FormRequest;

class SitterRequest extends FormRequest
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

        $sitter = $this->sitter ? $this->sitter : null;



        if ($sitter) {
            $password = 'nullable|min:6|confirmed';
            $city = 'nullable|exists:cities,id';
            $certificates = 'nullable|image|mimes:jpeg,jpg,png';
             if ($this->file_type == 'file') {
            $certificates = 'nullable|file|mimes:pdf';
        }
        }else{
            $password = 'required|min:6|confirmed';
            $city = 'required|exists:cities,id';
            $certificates = 'required|image|mimes:jpeg,jpg,png';
            if ($this->file_type == 'file') {
                $certificates = 'required|file|mimes:pdf';
            }
        }
        // dd($this->services);
        return [
            'name' => 'required|string|between:2,100',
            'phone' => 'required|numeric|digits_between:5,20|starts_with:9665,05|unique:users,phone,' . $sitter,

            'identity_number' => 'required|numeric|unique:users,identity_number,' . $sitter,
            'password' => $password,
            'image'    => 'nullable|image|mimes:jpeg,jpg,png,gif',
            // 'cover' => 'nullable|image|mimes:jpeg,jpg,png,gif',
            'gender' => 'nullable|in:male,female',
            'city_id'=>$city,
            'certificates' => $certificates,
            'services'=>'required|array',
            'services.*.service_id'=>'nullable|required_with:services.*.price|exists:services,id',
            'services.*.price'=>'required_with:services.*.service_id',
            'features'=>'required|array',
            'features.*'=>'nullable|exists:features,id',
            'is_active' => 'nullable|in:1,0',
            'is_ban' => 'nullable|in:1,0',
            'ban_reason' => 'nullable|string|between:3,10000'
        ];
    }

    public function getValidatorInstance()
    {
       $data = $this->all();
       if (isset($data['phone']) && $data['phone']) {
           $data['phone'] = filter_mobile_number($data['phone']);
       }
       if (isset($data['identity_number']) && $data['identity_number']) {
           $data['identity_number'] = convertArabicNumber($data['identity_number']);
       }
       if(isset($data['user_type'])  && isset($data['first_name']) && isset($data['last_name']))
       {
           $data['name']=$data['first_name'].' ' .$data['last_name'];
       }

       $data['file_type'] = 'image';
       if (isset($data['certificates']) && $data['certificates'] != null) {
           if($data['certificates']->getClientMimeType() == 'application/pdf') {
               $data['file_type'] = 'file';
           }
       }


       $this->getInputSource()->replace($data);
       return parent::getValidatorInstance();
    }
}
