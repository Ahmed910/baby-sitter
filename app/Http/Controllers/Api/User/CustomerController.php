<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\User\CustomerRateResource;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function getCustomerProfile($customer_id)
    {
        
        $user = User::findOrFail($customer_id);
        return (new CustomerRateResource($user))->additional(['status'=>'success','message'=>'']);
    }
}
