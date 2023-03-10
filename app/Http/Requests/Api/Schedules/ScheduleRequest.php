<?php

namespace App\Http\Requests\Api\Schedules;

use App\Http\Requests\Api\ApiMasterRequest;
use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends ApiMasterRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->schedule){
            return [
                'from'=>'nullable|date_format:H:i',
                'to'=>'nullable|date_format:H:i|after:from',
                'days'=>'nullable|array',
                'days.*'=>'nullable|exists:days,id'
            ];
        }else{
            return [
                'from'=>'required|date_format:H:i',
                'to'=>'required|date_format:H:i|after:from',
                'days'=>'required|array',
                'days.*'=>'required|exists:days,id'
            ];
        }

    }
}
