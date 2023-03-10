<?php

namespace App\Traits;

use App\Classes\Statuses;
use App\Http\Resources\Api\Client\Order\SingleOrderResource;
use App\Http\Resources\Api\Notification\SenderResource;
use App\Models\{CenterOrder, MainOrder,SitterOrder,User,Wallet};
use App\Notifications\Orders\CompleteOrderNotification;
use App\Notifications\Orders\DeliverChildernNotification;
use Illuminate\Support\Facades\{DB,Notification};

trait CompleteOrderHourService
{
    use Order,FinancialCenterOrder,FinancialSitterOrder;
    public function completeOrderForHourService(MainOrder $order,$otp_code)
    {

            $sitter_order = SitterOrder::where(['status' => Statuses::WITHTHECHILD, 'otp_code' => $otp_code, 'main_order_id' => $order->id])->first();
            if(!$sitter_order){
                return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.otp_is_not_valid')], 400);
            }
        DB::beginTransaction();

        try {

            $this->updateFinancialSitterData($order,$sitter_order);
            DB::commit();
            $fcm_notes = [
                'title' => ['dashboard.notification.sitter_has_been_deliver_childern_title'],
                'body' => ['dashboard.notification.sitter_has_been_deliver_childern_body', ['body' => auth('api')->user()->name ?? auth('api')->user()->phone]],
                'sender_data' => new SenderResource(auth('api')->user())
            ];
            $order->client->notify(new DeliverChildernNotification($order, ['database']));

            $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
            pushFcmNotes($fcm_notes, optional($order->client)->devices);
            Notification::send($admins, new DeliverChildernNotification($order, ['database', 'broadcast']));
            return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_is_valid')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
            // return true;
    }

    public function completeOrderForHourServiceForCenter(MainOrder $order)
    {
            $center_order = CenterOrder::where(['status' => Statuses::ACTIVE,'main_order_id' => $order->id])->first();
            // $sitter_order = SitterOrder::where(['status' => Statuses::WITHTHECHILD, 'otp_code' => $otp_code, 'main_order_id' => $order->id])->first();
            if(!$center_order){
                return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.data_not_found')], 404);
            }
        DB::beginTransaction();

        try {
            $this->updateFinancialCenterData($order,$center_order);
            $order->refresh();
            DB::commit();
            $fcm_notes = [
                'title' => trans('dashboard.notification.order_has_been_completed_title',[],$main_order->client->current_lang),
                'body' => trans('dashboard.notification.order_has_been_completed_body',[],$main_order->client->current_lang).auth('api')->user()->name,
                'sender_data' => new SenderResource(auth('api')->user())
            ];
            $order->client->notify(new CompleteOrderNotification($order, ['database']));

            $admins = User::whereIn('user_type', ['superadmin', 'admin'])->get();
            Notification::send($admins, new CompleteOrderNotification($order, ['database', 'broadcast']));
            pushFcmNotes($fcm_notes, optional($order->client)->devices);
            return (new SingleOrderResource($order))->additional(['status' => 'success', 'message' => trans('api.messages.order_has_been_completed')]);
            // return response()->json(['data' => null, 'status' => 'success', 'message' => trans('api.messages.otp_is_valid')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
            // return true;
    }


    public function completeOrderForHourUsingScanQrCode(MainOrder $order)
    {
        $sitter_order = SitterOrder::where(['status' => Statuses::WITHTHECHILD,  'main_order_id' => $order->id])->first();
        // $this->updateData($order,$sitter_order);
        $this->updateFinancialSitterData($order,$sitter_order);
    }

    private function updateData(MainOrder $order,$sitter_order)
    {
        $order->update(['finished_at' => now()]);

        // isset($otp_code) && $otp_code ? $sitter_order->update(['status'=>'completed','otp_code'=>NULL]) : $sitter_order->update(['status'=>'completed']);
        $sitter_order->update(['status'=>'completed','otp_code'=>null]);
        $sitter = User::findOrFail($sitter_order->sitter_id);
        $wallet_before = $sitter->wallet;
        $wallet_after = $wallet_before + $order->final_price;
        $this->chargeWallet($order->final_price, $sitter_order->sitter_id);
        if ($sitter_order->pay_type == 'wallet') {
            Wallet::create(['amount' => $order->final_price, 'wallet_before' => $wallet_before, 'wallet_after' => $wallet_after, 'user_id' => $order->sitter_id, 'transferd_by' => $order->client_id, 'order_id' => $order->id]);
        }
    }
}
?>
