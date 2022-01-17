<?php

namespace App\Http\Requests\Api\ChildCenter;

use App\Http\Requests\Api\ApiMasterRequest;
use App\Traits\ValidationTrait;
use Illuminate\Foundation\Http\FormRequest;

class OrderCenterRequest extends ApiMasterRequest
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
        $data=[
            'center_id' => 'required|exists:users,id',
            'baby_sitter_id' => 'required|exists:baby_sitters,id',
            'service_id' => 'required|exists:services,id',
            'kids'=>'required|array',
            'kid.*'=>'required|exists:kids,id',
            'comment'=>'nullable|between:3,10000',
            'transaction_id'=>'required',
            // 'price'=>'required|numeric',
            'check_order'=>'required|in:test,live',

            'pay_type'=>'required|in:credit,wallet',
            'transaction_id'=>'nullable|required_if:check_order,live|required_if:pay_type,credit',
            'price_before_offer'=>'required|numeric',
            'price_after_offer'=>'required|numeric',
            'discount'=>'required|numeric',
        ];

        $data = $this->getServiceType($data,$this->service_type);

        return $data;
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        // dd($data);
        $data2 = $this->editRequestData($data);

        $this->getInputSource()->replace($data2);
       return parent::getValidatorInstance();
    }
}
