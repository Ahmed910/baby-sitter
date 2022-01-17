<?php

namespace App\Http\Requests\Api\BabySitter;

use App\Http\Requests\Api\ApiMasterRequest;
use App\Models\Service;
use App\Models\UserService;
use App\Traits\ValidationTrait;
use Illuminate\Foundation\Http\FormRequest;
use OrderRequest;


class OrderSitterRequest extends ApiMasterRequest
{
    use ValidationTrait;
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
        // dd($this->service_type);
        // $data=[];

        $data=[
            'sitter_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'lat'=>'required|numeric',
            'lng'=>'required|numeric',
            'location'=>'required|between:3,250',
            'kids'=>'required|array',
            'kid.*'=>'required|exists:kids,id',
            'comment'=>'nullable|between:3,10000',
            'pay_type'=>'required|in:credit,wallet',
            'check_order'=>'required|in:test,live',
            'transaction_id'=>'nullable|required_if:check_order,live|required_if:pay_type,credit',
            'price_before_offer'=>'required|numeric',
            'price_after_offer'=>'required|numeric',
            'discount'=>'required|numeric',
        ];

        $data = $this->getServiceType($data,$this->service_type);

        return $data;
    }

    // public function validateOrderRequest()
    // {
    //     return [
    //          'sitter_id' => 'required|exists:users,id',
    //          'service_id'=>'required|exists:services,id',

    //     ];
    // }

    public function getValidatorInstance()
    {
        $data = $this->all();
        // dd($data);
        $data2 = $this->editRequestData($data);

        $this->getInputSource()->replace($data2);
       return parent::getValidatorInstance();
    }
}
