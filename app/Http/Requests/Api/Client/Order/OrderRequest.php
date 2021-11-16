<?php

namespace App\Http\Requests\Api\Client\Order;

use App\Http\Requests\Api\ApiMasterRequest;

class OrderRequest extends ApiMasterRequest
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
        $budget = 0;
        switch ($this->order_type) {
            case 'trip':
                $budget = (float)setting("min_trip_offer_price");
                break;
            case 'city_to_city':
                $budget = (float)setting("min_city_to_city_offer_price");
                break;
            case 'delivery':
                $budget = (float)setting("min_delivery_offer_price");
                break;
            case 'trucks':
                $budget = (float)setting("min_trucks_offer_price");
                break;
        }

        return [
           'start_location' => 'required|array',
           'start_location.lat' => 'required|numeric',
           'start_location.lng' => 'required|numeric',
           'start_location.location' => 'required|string|between:2,250',

           'end_location' => 'required|array',
           'end_location.*' => 'required|array',
           'end_location.*.lat' => 'required|numeric',
           'end_location.*.lng' => 'required|numeric',
           'end_location.*.location' => 'required|string|between:2,250',

           'car_type_id' => 'required|exists:car_types,id,deleted_at,NULL',
           'budget' => 'required|numeric|gte:'.($budget ?? 10),
           'order_type' => 'required|in:trip,city_to_city,delivery,trucks',
           'order_details' => 'nullable|required_if:order_type,delivery|string|between:3,1000',

           'pay_type' => 'nullable|in:cash,card,wallet,apple_pay',
           'transaction_id' => 'nullable|required_if:pay_type,card,apple_pay',


           'distance' => 'required|numeric|gte:0',
           'expected_time' => 'required|numeric|gte:0',
           'expected_route' => 'nullable|string|between:2,900000',
        ];
    }
}
