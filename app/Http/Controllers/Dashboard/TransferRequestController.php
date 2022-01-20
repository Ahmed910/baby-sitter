<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use DB;

class TransferRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transfer_requests = Wallet::whereNotNull('wallet_withdraw_id')->whereNull('order_id')->where(['transaction_type'=>'withdraw'])->paginate(100);
        return view('dashboard.transfer_request.index',compact('transfer_requests'));
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transfer_request = Wallet::with('walletWithdraw')->findOrFail($id);
        return view('dashboard.transfer_request.show',compact('transfer_request'));
    }

    public function accept($id)
    {
        $transfer_request = Wallet::where('status','pending')->findOrFail($id);
        $user = User::findOrFail($transfer_request->user_id);
        $wallet_before = $user->wallet;
        $wallet_after = $user->wallet - $transfer_request->amount;
        DB::beginTransaction();

        try {
            $transfer_request->update(['status'=>'accepted','transferd_by'=>auth()->id(),'wallet_before'=>$wallet_before,'wallet_after'=>$wallet_after]);
            $user->update(['wallet'=>$wallet_after]);
            DB::commit();
            return redirect(route('dashboard.transfer_request.index'))->withTrue(trans('dashboard.messages.success_transfer'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('dashboard.transfer_request.index'))->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
        }
    }

    public function reject($id)
    {
        $transfer_request = Wallet::where('status','pending')->findOrFail($id);
        $user = User::findOrFail($transfer_request->user_id);
        $wallet_before = $user->wallet;

        DB::beginTransaction();

        try {
            $transfer_request->update(['status'=>'rejected','wallet_before'=>$wallet_before,'wallet_after'=>$wallet_before]);

            DB::commit();
            return redirect(route('dashboard.transfer_request.index'))->withTrue(trans('dashboard.messages.reject_transfer'));

        } catch (\Exception $e) {
            DB::rollback();
            return redirect(route('dashboard.transfer_request.index'))->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
        }
    }


}
