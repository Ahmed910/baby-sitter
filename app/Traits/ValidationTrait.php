<?php

namespace App\Traits;

use App\Models\Service;
use App\Models\UserService;

trait ValidationTrait
{
    public function editRequestData($data)
    {

        if (isset($data['service_id'])) {
            $service = Service::findOrFail($data['service_id']);
            $user = isset($data['sitter_id']) ? $data['sitter_id'] : $data['center_id'];

            $user_services = UserService::where(['service_id'=>$data['service_id'],'user_id'=>$user])->firstOrFail();

            switch ($service->service_type) {
                case 'hour':
                    $data['service_type'] = 'hour';
                    break;
                default:
                    $data['service_type'] = 'month';
                    // break;
            }
        }

        return $data;
    }

    public function getServiceType($data,$service_type)
    {

        if($service_type == 'hour'){
            $data['date'] = 'required|date_format:Y-m-d|after_or_equal:' . now()->format("Y-m-d");
            $data['start_time'] = 'required|date_format:H:i';
            $data['end_time'] = 'required|date_format:H:i|after:start_time';
         }
         else{
            $data['start_date'] = 'required|date_format:Y-m-d|after_or_equal:' . now()->format("Y-m-d");
            $data['end_date'] = 'required|date_format:Y-m-d|after_or_equal:' . now()->format("Y-m-d");
            $data['schedules'] = 'required|array';
            $data['schedules.*.day_id'] = 'required|exists:days,id|required_with:schedules.*.start_time,schedules.*.end_time';
           // $data['schedules.*.date'] = 'required|after_or_equal:start_date|before_or_equal:end_date|date_format:Y-m-d|required_with:schedules.*.start_time,schedules.*.end_time';
            $data['schedules.*.start_time'] = 'required|date_format:H:i';
            $data['schedules.*.end_time'] = 'required|date_format:H:i';
            $data['schedules.*.date'] = 'required|array';
            $data['schedules.*.date.*'] = 'required|date_format:Y-m-d|after_or_equal:start_date|before_or_equal:end_date';
         }

         return $data;
    }
}
?>
