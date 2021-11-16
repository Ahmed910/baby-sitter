<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'wallet' => (float) $this->wallet,
            'tax' => (float) setting('tax'),
            'min_amount_charge_driver' => (float) setting('min_amount_charge_driver'),
            'min_amount_charge_client' => (float) setting('min_amount_charge_client'),
            'free_wallet_balance' => (float) $this->free_wallet_balance,
            'min_limit_withdrawal' => (float) (setting('min_limit_withdrawal') ?? 50),
            'dept_amount' => auth('api')->user()->user_dept_to_app ?  - (float) auth('api')->user()->user_dept_to_app : 0,
            'amount_of_on_account_for_user' => (float) setting('amount_of_on_account_for_user'),
            'can_borrow' => (float) setting('amount_of_on_account_for_user') - (float) auth('api')->user()->user_dept_to_app > 0,
            'amount_borrow' => (float) setting('amount_of_on_account_for_user') - (float) auth('api')->user()->user_dept_to_app,
        ];
    }
}
