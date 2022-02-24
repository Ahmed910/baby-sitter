<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\WalletRequest;
use App\Http\Resources\Api\User\WalletTransactionResource;
use App\Http\Resources\Api\User\WalletTransationsResource;
use App\Models\MainOrder;
use App\Models\Wallet;
use App\Models\WalletWithdraw;
use Illuminate\Http\Request;

class WalletController extends Controller
{

    public function withdrawOrChargeWallet(WalletRequest $request)
    {
        if ($request->transaction_type == 'withdraw') {

            return $this->withdrawalWallet($request);
        }
        return $this->chargeWallet($request);
    }
    private function chargeWallet($request)
    {
        $user = auth('api')->user();
        $wallet_before = $user->wallet;
        $wallet_after = $request->amount + $wallet_before;
        Wallet::create(['amount'=>$request->amount,'wallet_before'=>$wallet_before,'wallet_after'=>$wallet_after,'transaction_type'=>$request->transaction_type,'transaction_id' => $request->transaction_id, 'user_id' => auth('api')->id()]);
        $user->update(['wallet'=>$wallet_after]);
        return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.wallet_has_been_cashout')]);
    }

    private function withdrawalWallet($request)
    {
        $withdraw_data = ['account_name', 'bank_name', 'account_number', 'iban_number'];
        $user = auth('api')->user();
        if ($request->amount > $user->wallet) {
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.your_balance_in_wallet_is_insufficiant')], 400);
        }


            $wallet_withdraw = WalletWithdraw::create(array_only($request->validated(), $withdraw_data));
            $wallet_withdraw->wallet()->create(['amount'=>$request->amount,'transaction_type'=>$request->transaction_type,'user_id' => auth('api')->id()]);
            $wallet_before = $user->wallet;
            $wallet_after = $wallet_before - $request->amount;
            $user->update(['wallet'=>$wallet_after]);
            
            return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.request_has_been_sent')]);

    }

    public function getTransactions()
    {
        $user = auth('api')->user();
        $transactions = Wallet::where('user_id',$user->id)->get();
        return WalletTransationsResource::collection($transactions)->additional(['user_wallet'=>$user->wallet,'status'=>'success','message'=>'']);
    }
}
