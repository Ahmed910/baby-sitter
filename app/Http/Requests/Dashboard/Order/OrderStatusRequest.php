<?php

namespace App\Http\Requests\Dashboard\Order;

use Illuminate\Foundation\Http\FormRequest;

class OrderStatusRequest extends FormRequest
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
            'order_status' => 'required|in:pending,client_recieve_offers,driver_finish,admin_finish,client_cancel,client_finish,shipped,client_cancel,driver_cancel,start_trip,admin_cancel',
        ];
    }
}
