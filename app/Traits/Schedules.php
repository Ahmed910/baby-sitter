<?php

namespace App\Traits;

use App\Http\Requests\Api\Schedules\ScheduleRequest;
use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

trait Schedules
{

    protected function createSchedule(ScheduleRequest $request)
    {

        DB::beginTransaction();

        try {

            $appointment = auth('api')->user()->appointments()->create(array_except($request->validated(), ['days']));
            $arr = [];
            foreach ($request->days as $day) {

                $arr[] = [
                    'day_id' => $day,
                    'appointment_id' => $appointment->id
                ];
            }

            $appointment->schedules()->createMany($arr);
            DB::commit();
           return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.schedules_created_successfully')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }

    protected function updateSchedules(ScheduleRequest $request,$id)
    {
       
        DB::beginTransaction();

        try {
            $appointment = Appointment::where('user_id', auth('api')->id())->findOrFail($id);

            $appointment->update(array_except($request->validated(), ['days']));
            if(isset($request->days)){
                $appointment->days()->sync($request->days);
            }




            DB::commit();
           return response()->json(['data'=>null,'status'=>'success','message'=>trans('api.messages.schedules_updated_successfully')]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['data' => null, 'status' => 'fail', 'message' => trans('api.messages.there_is_an_error_try_again')], 400);
        }
    }
}
