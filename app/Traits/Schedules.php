<?php

namespace App\Traits;

use App\Http\Requests\Api\Schedules\ScheduleRequest;
use App\Models\Appointment;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

trait Schedules{

    protected function createSchedule(ScheduleRequest $request)
    {
        dd($request->days);
        DB::beginTransaction();

try {

    $appointment = auth('api')->user()->create(array_except($request->validated(), ['days']));
    
    $appointment->schedules()->createMany();
    DB::commit();
    // all good
} catch (\Exception $e) {
    DB::rollback();
    return response()->json(['data'=>null,'status'=>'fail','message'=>trans('api.messages.there_is_an_error_try_again')],400);
}


    }
}

?>
