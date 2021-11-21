<?php

namespace App\Http\Controllers\Api\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Profile\{
    EditProfileRequest,
    UpdatePasswordRequest
};
use App\Http\Resources\Api\User\{UserProfileResource};
use App\Jobs\UpdateDriverLocation;
use App\Models\{ChildCentre, User};
use DB;

class UserController extends Controller
{
    // Show Profile
    public function index()
    {
        $user = auth('api')->user();
        if (!$user->referral_code) {
            $user->update(['referral_code' => generate_unique_code(8, '\\App\\Models\\User', 'referral_code', 'alpha_numbers', 'lower')]);
        }
        return (new UserProfileResource($user))->additional(['status' => 'success', 'message' => '']);
    }


    // Edit Profile
    public function store(EditProfileRequest $request)
    {

        DB::beginTransaction();
        try {
            $user = auth('api')->user();

            $profile_date = ['country_id','bio', 'city_id', 'is_infected', 'lat', 'lng', 'location'];
            $child_center = ['business_register', 'price','is_educational', 'business_license_image'];

            if ($user->user_type != 'client') {
                $services = $request->services;
                $arr = [];
                foreach ($services as $service) {
                    $arr[$service['service_id']] = ['price' => $service['price']];
                }
            }

            $user->update(array_except($request->validated(), array_merge($profile_date, $child_center)));

            $user->profile()->update(array_only($request->validated(), $profile_date));

            if ($user->user_type == 'childcenter') {

                //  $user->update(['is_admin_active_user'=>false]);
                $center = $user->child_centre;
                $center->update(array_only($request->validated(), $child_center));

                $user->services()->sync($arr);
            }
            if ($user->user_type == 'babysitter') {
                // $user->update(['is_admin_active_user'=>false]);
                $user->services()->sync($arr);
            }
            if ($request->device_token) {
                $user->devices()->firstOrCreate($request->only(['device_token']) + ['type' => 'ios']);
            }

            $msg = trans('api.messages.updated_successfully');
            DB::commit();
            return (new UserProfileResource($user))->additional(['status' => 'success', 'message' => $msg]);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::info($e->getMessage());
            return response()->json(['status' => 'fail', 'data' => null, 'message' => 'لم يتم التعديل حاول مرة اخرى'], 401);
        }
    }
    // Edit Password
    public function editPassword(UpdatePasswordRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = auth('api')->user();
            $user->update(array_only($request->validated(), ['password']));
            DB::commit();
            return (new UserProfileResource($user))->additional(['status' => 'success', 'message' => trans('api.messages.updated_successfully')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'fail', 'data' => null, 'message' => trans('api.messages.editing_is_not_done_try_again')], 401);
        }
    }


    public function updateUserLocation(Request $request)
    {
        $data = $request->all();
        if (isset($data['drivers'])) {
            $data = json_decode($data['drivers']);
            UpdateDriverLocation::dispatch($data)->onQueue('high');
        }
        return response()->json(['data' => $request->all()]);
    }
}
