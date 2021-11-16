<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{ User , Country , City , Chat , Message , MoneyTransfer , WalletTransaction};
use Illuminate\Http\Request;
use App\Http\Requests\Dashboard\User\ClientRequest;
use App\Http\Resources\Dashboard\Client\ClientResource;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::where('user_type' , 'client')->when($request->status,function ($q) use($request) {
            switch ($request->status) {
                case 'deactive':
                    $q->where('is_active',0);
                    break;
                case 'ban':
                    $q->where('is_ban',1);
                    break;
            }
        })->latest();
        $client_count = $query->count();
        $client_side_cols = [
            'id','image','fullname','email','phone','created_at'
        ];
        if (request()->ajax()) {
            $keyword = $request->search['value'];
            $clients = $query->when($keyword,function($q)use($keyword){
                $q->where(function($q)use($keyword){
                    $q->where('fullname',"LIKE","%{$keyword}%")->orWhere('email',"LIKE","%{$keyword}%")->orWhere('phone',"LIKE","%{$keyword}%");
                });
            });

            $client_count = $clients->count();
            $clients = $clients->when(isset($client_side_cols[$request['order'][0]['column']]),function ($q) use($request , $client_side_cols) {
                $q->orderBy($client_side_cols[$request['order'][0]['column']],$request['order'][0]['dir']);
            })->when(!isset($client_side_cols[$request['order'][0]['column']]),function ($q) {
                $q->latest();
            })->skip($request['start'])->take($request['length'] == '-1' ? $client_count : $request['length'])->get();

            return (new ClientResource($clients))->additional(['client_count' => $client_count]);
        }

        if (!request()->ajax()) {
          return view('dashboard.client.index',compact('client_count'));
        }
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      if (!request()->ajax()) {
          $countries = Country::get()->pluck('nationality','id');
          $cities = City::get()->pluck('name','id');
          return view('dashboard.client.create',compact('countries','cities'));
      }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {
        if (!request()->ajax()) {
            \DB::beginTransaction();
            try {
                $client= User::create(array_except($request->validated(),['country_id','city_id'])+['user_type' => 'client' , 'verified_code' => ($request->is_active ? null : 1111) , 'referral_code' => generate_unique_code(8,'\\App\\Models\\User','referral_code','alpha_numbers','lower')]);
                $client->profile()->create(array_only($request->validated(),['country_id','city_id'])+['added_by_id' => auth()->id()]);
                \DB::commit();
                if ($client->is_active) {
                    send_sms($client->phone,trans('dashboard.messages.now_u_can_login_to_app'));
                }
               return redirect(route('dashboard.client.index'))->withTrue(trans('dashboard.messages.success_add'));
            }catch (\Exception $e) {
                \DB::rollback();
                return redirect(route('dashboard.client.index'))->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
     public function show(Request $request , $id)
     {
         if (!request()->ajax()) {
             $client = User::where('user_type','client')->findOrFail($id);
             $data['client'] = $client;
             $data['orders'] = $client->clientOrders()->latest()->paginate(30);
             $data['points'] = $client->userPoints()->latest()->paginate(30);

             $data['other_clients'] = User::where('user_type','client')->where('id',"<>",$client->id)->inRandomOrder()->take(5)->get();
             $data['wallet_transfers'] = MoneyTransfer::where('transfer_to_id',$client->id)->orWhere('transfer_from_id',$client->id)->latest()->paginate(30);
             $data['wallet_transactions'] = WalletTransaction::where('user_id',$client->id)->orWhere('added_by_id',$client->id)->latest()->paginate(30);
             $data['total_clients'] = User::where('user_type','client')->count();
             return view('dashboard.client.show',$data);
         }
     }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!request()->ajax()) {
            $client = User::where('user_type','client')->findOrFail($id);
            $countries = Country::get()->pluck('nationality','id');
            $cities = City::get()->pluck('name','id');
            return view('dashboard.client.edit',compact('client','countries','cities'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $request, $id)
    {
        if (!request()->ajax()) {
            $client = User::where('user_type','client')->findOrFail($id);
            $is_active = $client->is_active;
            \DB::beginTransaction();
            try {
                $client->update(array_except($request->validated(),['country_id','city_id'])+['user_type' => 'client','verified_code' => $request->is_active ? null : 1111]);
                $client->profile()->updateOrCreate(['user_id' => $client->id],array_only($request->validated(),['country_id','city_id']));
                \DB::commit();
                if (!$is_active && $client->is_active) {
                    send_sms($client->phone,trans('dashboard.messages.now_u_can_login_to_app'));
                }
                return redirect(route('dashboard.client.index'))->withTrue(trans('dashboard.messages.success_update'));
            }catch (\Exception $e) {
                \DB::rollback();
                return redirect(route('dashboard.client.index'))->withFalse(trans('dashboard.messages.something_went_wrong_please_try_again'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $client = User::where('user_type','client')->findOrFail($id);
        if ($client->delete()) {
          return response()->json(['value' => 1]);
        }
    }
}
