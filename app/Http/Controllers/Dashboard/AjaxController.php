<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    AvailableDay,
    User, City , Brand,
    CarModel, Car , Package,
    Order, Driver , Product
};
use App\Http\Requests\Dashboard\User\{
    AcceptDriverDataRequest, ChangeDriverTypeRequest
};
use App\Http\Requests\Dashboard\Order\{OrderStatusRequest};
use App\Http\Requests\Dashboard\Package\{
    UpdateSubscribtionRequest, SubscribePackageRequest , SubscribeAllDriversPackageRequest
};
use App\Notifications\General\{GeneralNotification , FCMNotification};
use App\Notifications\Order\{ChangeOrderStatusNotification};
use App\Http\Requests\Dashboard\WalletTransaction\{WalletTransactionRequest};
use App\Jobs\{UpdateWallet , ChangePackageOfDrivers , UpdateWalletByTempBalance ,UpdateTempWallet};
use App\Services\{WaslElmService};
use Carbon\Carbon;

class AjaxController extends Controller
{
    use WaslElmService;

    public function checkIfTempBalance(Request $request)
    {
        $wallet_temps = TemporaryWallet::finished()->where('is_expired',0)->get();

        foreach ($wallet_temps as $wallet) {
            $wallet->update(['is_expired' => 1]);
            // $wallet->user->update(['wallet' => \DB::raw('wallet -'.$wallet->rest_amount)]);
            $new_wallet = wallet_transaction($wallet->user, $wallet->rest_amount, 'withdrawal', $wallet);
            $wallet->user->update(['wallet' => $new_wallet]);
        }
        return response()->json(['value' => 1 , 'message' => trans('dashboard.messages.success_update')]);
    }

    public function getUsersByType($user_type = 'client')
    {
        $users = User::where('user_type',$user_type)->get();
        $view = view('dashboard.ad.ajax.'.$user_type,compact('users'))->render();
        return response()->json(['value' => 1 , 'view' => $view]);
    }

    public function getAvailableDaysByDistrict($district_id)
    {
        $available_days = AvailableDay::where('district_id',$district_id)->get();

        $view = view('dashboard.favorite_time.ajax.available_day',compact('available_days'))->render();
        return response()->json(['value' => 1 , 'view' => $view]);
    }

    public function getCarModelsByBrand($brand_id)
    {
        $car_models = CarModel::where('brand_id',$brand_id)->get()->pluck('name','id');
        $view = view('dashboard.car.ajax.car_model',compact('car_models'))->render();
        return response()->json(['value' => 1 , 'view' => $view]);
    }

    public function EnableDriverData(AcceptDriverDataRequest $request , $driver_id)
    {
        $driver = Driver::where('user_id',$driver_id)->firstOrFail();
        $driver_data = ['is_admin_accept' => $request->is_admin_accept,'accepted_status' => ($request->is_admin_accept ? 'accepted' : 'refused') , 'refuse_reason' => $request->refuse_reason];

        if ($driver->is_admin_accept && setting('register_in_elm') == 'with_accept_data') {
            $elm_reply = $this->registerDriver($driver);
            $driver_data += ['elm_reply' => $elm_reply];
            if (@$elm_reply['resultCode'] == 'success') {
                $driver_data += ['is_signed_to_elm' => true];
            }
        }
        $driver->update($driver_data);

        $fcm_data =[
            'title' => trans('dashboard.fcm.car_data_statuses_title.'.($driver->is_admin_accept ? 1 : 0)),
            'body' => $request->refuse_reason ?? trans('dashboard.fcm.car_data_statuses_body.'.($driver->is_admin_accept ? 1 : 0)),
            'notify_type' => 'change_car_status',
        ];
        $text = $driver->is_admin_accept ? trans('dashboard.driver.admin_accept') : trans('dashboard.driver.admin_refuse');
        $text_class = $driver->is_admin_accept ? 'text-success' : 'text-danger';
        $removed_class = $driver->is_admin_accept ? 'text-danger' : 'text-success';
        $accept_btn = $driver->is_admin_accept ? 'disabled' : false;
        $refuse_btn = !$driver->is_admin_accept ? 'disabled' : false;
        $driver->user->notify(new FCMNotification($fcm_data,['database']));
        pushFcmNotes($fcm_data,[$driver->user_id]);
        return response()->json(['value' => 1 ,'is_admin_accept' => $driver->is_admin_accept , 'text_class' => $text_class , 'removed_class' => $removed_class  , 'text' => $text  , 'accept_btn' => $accept_btn , 'refuse_btn' => $refuse_btn]);
    }

    public function changeDriverType(ChangeDriverTypeRequest $request , $driver_id)
    {
        $driver = Driver::where('user_id',$driver_id)->firstOrFail();

        $driver->update(['driver_type' => $request->driver_type]);

        $text = trans('dashboard.driver.driver_types.'.$driver->driver_type);

        return response()->json(['value' => 1 , 'text' => $text]);
    }


    public function getElmReply(Request $request , $driver_id)
    {
        $driver = User::where('user_type','driver')->findOrFail($driver_id);
        $elm_reply = $driver->driver->elm_reply;
        if (!$elm_reply) {
            $elm_reply = $this->driverVehicleEligibility($driver);
            $driver->driver()->update(['elm_reply' => $elm_reply]);
        }
        return response()->json(['value' => 1 ,'resultCode' => @$elm_reply['resultCode'] ?? $driver->fullname ,'resultMsg' => @$elm_reply['resultCode'] ? @$elm_reply['resultMsg'] : trans('dashboard.driver.elm.no_data_returned')]);
    }

    public function registerDriverToElm(Request $request , $driver_id)
    {
        $driver = User::where('user_type','driver')->findOrFail($driver_id);
        $elm_reply = $this->registerDriver($driver);
        if ($elm_reply) {
            $driver_data = ['elm_reply' => $elm_reply];
            if (@$elm_reply['resultCode'] == 'success') {
                $driver_data += ['is_signed_to_elm' => true];
            }
            $driver->driver()->update($driver_data);
        }
        return response()->json(['value' => 1 ,'resultCode' => @$elm_reply['resultCode'],'resultMsg' => @$elm_reply['resultMsg']]);
    }

    public function getSearch(Request $request)
    {
        $query = request()->query('query');
        $clients = User::where('user_type','client')->where(function($q)use($query){
            $q->where('fullname',"LIKE","%{$query}%")->orWhere('email',"LIKE","%{$query}%")->orWhere('phone',"LIKE","%{$query}%");
        })->get();

        $drivers = User::where('user_type','driver')->where(function($q)use($query){
            $q->where('fullname',"LIKE","%{$query}%")->orWhere('email',"LIKE","%{$query}%")->orWhere('phone',"LIKE","%{$query}%");
        })->get();

        $admins = User::where('user_type','admin')->where(function($q)use($query){
            $q->where('fullname',"LIKE","%{$query}%")->orWhere('email',"LIKE","%{$query}%")->orWhere('phone',"LIKE","%{$query}%");
        })->where('id',"<>",auth()->id())->get();

        $brands = Brand::whereTranslationLike('name',"%{$query}%",'ar')->orWhereTranslationLike('name',"%{$query}%",'en')->orWhereTranslationLike('desc',"%{$query}%",'ar')->orWhereTranslationLike('desc',"%{$query}%",'en')->get();

        $car_models = CarModel::whereTranslationLike('name',"%{$query}%",'ar')->orWhereTranslationLike('name',"%{$query}%",'en')->orWhereTranslationLike('desc',"%{$query}%",'ar')->orWhereTranslationLike('desc',"%{$query}%",'en')->get();


        $collection = $clients->merge($admins);
        $collection = $collection->merge($drivers);
        $collection = $collection->merge($admins);
        $collection = $collection->merge($brands);
        $collection = $collection->merge($car_models);
        $view = view('dashboard.layout.ajax.search',compact('collection'))->render();
        return response()->json(['value' => 1 , 'view' => $view]);
    }

    public function enablePackageActive($package_id)
    {
        $package = Package::findOrFail($package_id);
        $package->update(['is_active' => !$package->is_active]);
        return response()->json(['value' => 1 ,'is_active' => $package->is_active ,'message' => trans('dashboard.messages.success_update')]);
    }

    public function enableProductActive($product_id)
    {
        $product = Product::findOrFail($product_id);
        $product->update(['is_active' => !$product->is_active]);
        return response()->json(['value' => 1 ,'is_active' => $product->is_active ,'message' => trans('dashboard.messages.success_update')]);
    }

    public function deleteAppImage(Request $request , $id)
    {
        $image = AppImage::findOrFail($id);
        $image->delete();
        if (file_exists(storage_path('app/public/images/'.$request->class_name.'/'.$image->image))){
            \File::delete(storage_path('app/public/images/'.$request->class_name.'/'.$image->image));
        }
        return response()->json(['value' => 1]);
    }

    public function generateCode($length = 8 , $type = 'numbers' , $model = 'Coupon' ,$col = 'code', $letter_type = 'all')
    {
        $model_name = '\\App\\Models\\' . $model;
        return generate_unique_code($length, $model_name ,$col ,$type ,$letter_type);
    }

    public function updateOrderStatus(OrderStatusRequest $request , $order_id)
    {
        $order = Order::findOrFail($order_id);
        if ($request->order_status) {
            if (in_array($request->order_status,['admin_finish','client_finish','driver_finish']) && ! $order->finished_at && $order->driver_id) {
                $driver = $order->driver;
                $driver_wallet = (float)$driver->wallet;
                $order->update(['finished_at' => now()]);
                if ($order->driver_id) {
                    $order->driver->driver()->updateOrCreate(['user_id' => $order->driver_id],['is_available' => 1]);
                    if ($order->driver->driver->is_on_default_package) {
                        if ($order->driver->driver->free_order_counter < setting('number_of_free_orders_on_default_package')) {
                            $order->driver->driver()->update(['free_order_counter' => \DB::raw('free_order_counter + 1')]);
                        }
                        $order->driver()->update(['wallet' => ($driver_wallet -((float)setting('price_of_default_package_order') ?? 1 ))]);
                    }
                }
                if ($order->order_status == 'start_trip') {
                    $wallet_amount = 0;

                    $client = $order->client;
                    if ($order->is_paid_by_wallet) {
                        $free_wallet_balance = $client->free_wallet_balance - $order->total_price <= 0 ? 0 : ($client->free_wallet_balance - $order->total_price);
                        $client->update(['wallet' => ($client->wallet - $order->total_price),'free_wallet_balance' => $free_wallet_balance]);
                        $wallet_amount = $order->total_price;
                    }
                    $start_at = date("Y-m-d H:i:s",strtotime(optional($order->order_status_times)->start_trip));
                    $order->update(['actual_time' => now()->diffInMinutes($start_at) ?? $order->expected_time,'wallet_amount' => $wallet_amount]);

                    if ($order->is_paid_by_wallet) {
                        $driver->update(['wallet' => ($driver_wallet + (float)$wallet_amount)]);
                    }
                }
            }
            if (in_array($request->order_status,['admin_cancel','client_cancel','driver_cancel','shipped']) && $order->driver_id && (!$order->finished_at || $order->order_status == 'start_trip')) {
                $order->driver->driver()->updateOrCreate(['user_id' => $order->driver_id],['is_available' => 1]);
            }
            $order->update(['order_status' => $request->order_status,'order_status_times' => [$request->order_status => date("Y-m-d h:i A")]]);
            $admins = User::whereIn('user_type',['admin','superadmin'])->get();
            \Notification::send($admins,new ChangeOrderStatusNotification($order));

            return response()->json(['value' => 1 ,'message' => trans('dashboard.messages.success_update')]);
        }else{
            return response()->json(['value' => 0 ,'message' => trans('dashboard.messages.no_data_found')]);
        }
    }

    public function updateWalletTransferStatus(WalletTransactionRequest $request , $wallet_id)
    {
        $wallet_transfer = WalletTransaction::pending()->where('transaction_type','withdrawal')->findOrFail($wallet_id);
        if ($request->transfer_status == 'refused') {
            $wallet_transfer->user()->update(['wallet' => ($wallet_transfer->user->wallet + $wallet_transfer->amount) , 'free_wallet_balance' => ($wallet_transfer->user->free_wallet_balance + $wallet_transfer->free_wallet_balance)]);
        }
        $wallet_transfer->update(['transfer_status' => $request->transfer_status , 'transfer_at' => ($request->transfer_status == 'transfered' ? now() : null)]);

        // \Mail::to($wallet_transfer->email)->send(new ReplyWalletTransaction($reply));
        $pushFcmNotes = [
            'title' => trans('dashboard.fcm.transfer_request'),
            'body' => trans('dashboard.fcm.transfer_statuses.'.$request->transfer_status),
            'notify_type' => 'management',
        ];
        // if ($request->send_type == 'fcm') {
            pushFcmNotes($pushFcmNotes, [$wallet_transfer->user_id]);
        // }else{
            // send_sms($wallet_transfer->user->phone,$pushFcmNotes['body']);
        // }
        \Notification::send($wallet_transfer->user,new GeneralNotification($pushFcmNotes+['wallet_id' => $wallet_transfer->id]));

        return response()->json(['value' => 1 ,'message' => trans('dashboard.messages.success_send')]);

    }

    public function getNewOrders(Request $request)
    {
        $orders = Order::withTrashed()->latest()->whereIn('order_status',['pending','client_recieve_offers'])->paginate(10);
        $css_class = 'text-warning';
        $view = view('dashboard.home.ajax.order',compact('orders','css_class'))->render();
        return response()->json(['value' => 1 ,'view' => $view]);
    }

    public function getCurrentOrders(Request $request)
    {
        $orders = Order::withTrashed()->latest()->whereIn('order_status',['shipped'])->paginate(10);
        $css_class = 'text-primary';
        $view = view('dashboard.home.ajax.order',compact('orders','css_class'))->render();
        return response()->json(['value' => 1 ,'view' => $view]);
    }

    public function getFinishedOrders(Request $request)
    {
        $orders = Order::withTrashed()->latest()->whereIn('order_status',['client_finish','driver_finish','admin_finish'])->paginate(10);
        $css_class = 'text-success';
        $view = view('dashboard.home.ajax.order',compact('orders','css_class'))->render();
        return response()->json(['value' => 1 ,'view' => $view]);
    }

    public function UpdatePackageEndDate(UpdateSubscribtionRequest $request , $package_id , $driver_id)
    {
        $package = PackageDriver::findOrFail($package_id);
        $style_befor = $package->subscribe_status_css;
        $driver = User::where('user_type','driver')->findOrFail($driver_id);
        $end_date = \Carbon\Carbon::parse($request->end_at);
        $package->update(['end_at' => $request->end_at , 'subscribe_status' => (now()->gt($end_date) ? 'finished' : 'subscribed')]);

        return response()->json(['value' => 1 , 'message' => trans('dashboard.messages.success_update') , 'end_date' => $package->end_at->format("Y-m-d") , 'package_status' => $package->subscribe_status , 'package_status_css' => $package->subscribe_status_css , 'style_before' => $style_befor]);
    }
}
