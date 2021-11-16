<?php

namespace App\Http\Requests\Dashboard\User;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
        if ($this->user_id == 'all') {
            $user = 'nullable|in:all';
        }else{
            $user = 'required|exists:users,id';
        }
        return [
            'user_id' => $user,
            // 'send_type' => 'required|in:fcm,sms',
            'user_type' => 'nullable|in:driver,client',
            'user_list' => 'nullable|array',
            'user_list.*' => 'nullable|exists:users,id',
            'status' => 'nullable|in:deactive,ban,accepted,paid,wait_accept_drivers,refused_drivers,available,not_available,drivers_subscribed_this_week,with_special_needs,both_type,delivery,ride,monthly_drivers,on_order_drivers,has_balance_in_wallet',
            'title' => 'required|string|between:3,200',
            'body' => 'required|string|between:3,10000',
        ];
    }
}
