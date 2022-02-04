<?php

namespace App\Http\Requests\Dashboard\Setting;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        // dd($this);
        return [
          'email' => "required|email",
          'phones.*' => "nullable|numeric",
          'driver_notify_count_to_refuse' => "nullable|integer|between:1,5",
          'waiting_time_for_driver_response' => "nullable|numeric|gte:1",
          'test_version' => "nullable",
          'app_commission' => "nullable|numeric|between:0,50",
          'tax' => "nullable|numeric|between:0,50",
          'search_distance' => "nullable|numeric|gte:0",
          'min_offer_price' => "nullable|numeric|gte:0",
          'min_limit_withdrawal' => "nullable|numeric|gte:10",
          'min_amount_in_transfer_transaction' => "nullable|integer|gte:1",
          'number_of_free_orders_on_default_package' => "nullable|numeric|gte:0",
          'min_wallet_to_recieve_order' => "nullable|numeric|gte:0",
          'min_amount_charge_driver' => "nullable|integer|gte:1",
          'min_amount_charge_client' => "nullable|integer|gte:1",
          'number_of_cars_on_map' => "nullable|integer|gte:0",
          'price_of_default_package_order' => "nullable|numeric|gte:0",
          'num_points_when_client_register_by_refer_code' => "nullable|numeric|gte:0",
          'num_points_when_driver_register_by_refer_code' => "nullable|numeric|gte:0",
          'min_wallet_amount_found_when_order' => "nullable|numeric|gte:0",
          'number_drivers_to_notify' => "nullable|integer|gt:0",
          'number_of_days_to_update_special_needs_client_wallet' => "nullable|integer|gt:0",
          'amount_of_special_needs_help' => "nullable|integer|gt:0",
          'waiting_time_to_cancel_order' => "nullable|integer|gt:0",
          'waiting_time_to_finish_order' => "nullable|integer|gt:0",
          'amount_of_on_account_for_user' => "nullable|integer|gt:0",
          'min_amount_in_wallet_to_use_salfni' => "nullable|integer|gt:0",
          'map_api' => "nullable|string|between:2,300",
          'is_payment_showing' => "nullable|in:enable,disable",
          'register_in_elm' => "nullable|in:after_register,with_accept_data,register_manually",
          'enable_make_order_and_take_order' => "nullable|in:1,0",
          'second_trip_max_price' => "nullable|required_if:enable_make_order_and_take_order,1|numeric|gt:0",
          'number_of_repeat_order_offer' => "nullable|required_if:enable_make_order_and_take_order,1|integer|gt:0",
          // SMS
          'use_sms_service' => "nullable|in:enable,disable",
          'app_profit_percentage'=>'nullable|numeric|min:0|max:100',
          'offer_fees'=>'nullable|numeric',
          'sms_provider' => "nullable|required_if:use_sms_service,enable|in:hisms,net_powers,sms_gateway",
          'sms_username' => "nullable|required_if:use_sms_service,enable|string|between:3,250",
          'sms_password' => "nullable|required_with:sms_username",
          'sms_sender_name' => "nullable|string|between:3,250",

          'project_name' => "required",
          'desc_ar' => "nullable|string|between:3,100000",
          'desc_en' => "nullable|string|between:3,100000",
          'meta_ar' => "nullable|string|between:3,100000",
          'meta_en' => "nullable|string|between:3,100000",
          'policy_ar' => "nullable|string|between:3,100000",
          'policy_en' => "nullable|string|between:3,100000",
          'terms_ar' => "nullable|string|between:3,100000",
          'terms_en' => "nullable|string|between:3,100000",
          'home_msg_ar' => "nullable|string|between:3,100000",
          'home_msg_en' => "nullable|string|between:3,100000",
          'driver' => "nullable",
          'host' => "nullable",
          'port' => "nullable",
          'from_name' => "nullable",
          'from_address' => "nullable",
          'username' => "nullable",
          'password' => 'nullable',
          'encry' => "nullable|in:tls,ssl",
          'logo' => "image|mimes:png,jpg,jpeg",
          'facebook' => "nullable|url",
          'twitter' => "nullable|url",
          'youtube' => "nullable|url",
          'instagram' => "nullable|url",
          'whatsapp' => "nullable||string|max:250",
          'sms_message' => "nullable||string|max:250",
          'address' => "nullable|string|max:250",
          'g_play_app' => "nullable|url",
          'app_store_app' => "nullable|url",
          'huawei_store_app' => "nullable|url",
        ];
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        if (isset($data['phones']) && $data['phones'] != null) {
            $data['phones'] = array_map('trim',explode(",",$data['phones']));
        }

        if (isset($data['min_limit_withdrawal']) && $data['min_limit_withdrawal'] != null) {
            $data['min_limit_withdrawal'] = convertArabicNumber($data['min_limit_withdrawal']);
        }

        if (isset($data['enable_make_order_and_take_order']) && $data['enable_make_order_and_take_order']) {
            $data['enable_make_order_and_take_order'] = 1;
        }else{
            $data['enable_make_order_and_take_order'] = 0;
        }

        if (isset($data['driver_notify_count_to_refuse']) && $data['driver_notify_count_to_refuse'] != null) {
            $data['driver_notify_count_to_refuse'] = convertArabicNumber($data['driver_notify_count_to_refuse']);
        }
        if (isset($data['waiting_time_for_driver_response']) && $data['waiting_time_for_driver_response'] != null) {
            $data['waiting_time_for_driver_response'] = convertArabicNumber($data['waiting_time_for_driver_response']);
        }

        if (isset($data['search_distance']) && $data['search_distance'] != null) {
            $data['search_distance'] = convertArabicNumber($data['search_distance']);
        }
        if (isset($data['min_offer_price']) && $data['min_offer_price'] != null) {
            $data['min_offer_price'] = convertArabicNumber($data['min_offer_price']);
        }
        if (isset($data['min_amount_in_transfer_transaction']) && $data['min_amount_in_transfer_transaction'] != null) {
            $data['min_amount_in_transfer_transaction'] = convertArabicNumber($data['min_amount_in_transfer_transaction']);
        }

        if (isset($data['number_of_free_orders_on_default_package']) && $data['number_of_free_orders_on_default_package'] != null) {
            $data['number_of_free_orders_on_default_package'] = convertArabicNumber($data['number_of_free_orders_on_default_package']);
        }

        if (isset($data['min_wallet_to_recieve_order']) && $data['min_wallet_to_recieve_order'] != null) {
            $data['min_wallet_to_recieve_order'] = convertArabicNumber($data['min_wallet_to_recieve_order']);
        }

        if (isset($data['min_amount_charge_driver']) && $data['min_amount_charge_driver'] != null) {
            $data['min_amount_charge_driver'] = convertArabicNumber($data['min_amount_charge_driver']);
        }

        if (isset($data['min_amount_charge_client']) && $data['min_amount_charge_client'] != null) {
            $data['min_amount_charge_client'] = convertArabicNumber($data['min_amount_charge_client']);
        }

        if (isset($data['number_of_cars_on_map']) && $data['number_of_cars_on_map'] != null) {
            $data['number_of_cars_on_map'] = convertArabicNumber($data['number_of_cars_on_map']);
        }

        if (isset($data['price_of_default_package_order']) && $data['price_of_default_package_order'] != null) {
            $data['price_of_default_package_order'] = convertArabicNumber($data['price_of_default_package_order']);
        }

        if (isset($data['num_points_when_client_register_by_refer_code']) && $data['num_points_when_client_register_by_refer_code'] != null) {
            $data['num_points_when_client_register_by_refer_code'] = convertArabicNumber($data['num_points_when_client_register_by_refer_code']);
        }

        if (isset($data['num_points_when_driver_register_by_refer_code']) && $data['num_points_when_driver_register_by_refer_code'] != null) {
            $data['num_points_when_driver_register_by_refer_code'] = convertArabicNumber($data['num_points_when_driver_register_by_refer_code']);
        }

        if (isset($data['min_wallet_amount_found_when_order']) && $data['min_wallet_amount_found_when_order'] != null) {
            $data['min_wallet_amount_found_when_order'] = convertArabicNumber($data['min_wallet_amount_found_when_order']);
        }

        if (isset($data['number_drivers_to_notify']) && $data['number_drivers_to_notify'] != null) {
            $data['number_drivers_to_notify'] = convertArabicNumber($data['number_drivers_to_notify']);
        }

        if (isset($data['number_of_days_to_update_special_needs_client_wallet']) && $data['number_of_days_to_update_special_needs_client_wallet'] != null) {
            $data['number_of_days_to_update_special_needs_client_wallet'] = convertArabicNumber($data['number_of_days_to_update_special_needs_client_wallet']);
        }

        if (isset($data['amount_of_special_needs_help']) && $data['amount_of_special_needs_help'] != null) {
            $data['amount_of_special_needs_help'] = convertArabicNumber($data['amount_of_special_needs_help']);
        }

        if (isset($data['waiting_time_to_cancel_order']) && $data['waiting_time_to_cancel_order'] != null) {
            $data['waiting_time_to_cancel_order'] = convertArabicNumber($data['waiting_time_to_cancel_order']);
        }

        if (isset($data['waiting_time_to_finish_order']) && $data['waiting_time_to_finish_order'] != null) {
            $data['waiting_time_to_finish_order'] = convertArabicNumber($data['waiting_time_to_finish_order']);
        }

        if (isset($data['amount_of_on_account_for_user']) && $data['amount_of_on_account_for_user'] != null) {
            $data['amount_of_on_account_for_user'] = convertArabicNumber($data['amount_of_on_account_for_user']);
        }

        if (isset($data['min_amount_in_wallet_to_use_salfni']) && $data['min_amount_in_wallet_to_use_salfni'] != null) {
            $data['min_amount_in_wallet_to_use_salfni'] = convertArabicNumber($data['min_amount_in_wallet_to_use_salfni']);
        }

        $this->getInputSource()->replace($data);
        return parent::getValidatorInstance();
    }

}
