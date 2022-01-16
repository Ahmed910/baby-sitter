<?php

namespace App\Http\Resources\Api\User;

use Illuminate\Http\Resources\Json\JsonResource;

class RateForSpecificOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {


        return [
            'id' => $this->id,
            'user_data' =>$this->UserData($this->type),
            'rate'=>$this->rate,
            'review'=>$this->review
        ];
    }


    private function UserData($type)
    {
        $user_data=[];
        switch($type){
            case 'sitter':
                $user_data = [
                    'id' => optional($this->toUser)->id,
                    'name' => optional($this->toUser)->name,
                    'avatar' => optional($this->toUser)->avatar,
               ];
               break;
            case 'client':
                $user_data = [
                    'id' => optional($this->toClient)->id,
                    'name' => optional($this->toClient)->name,
                    'avatar' => optional($this->toClient)->avatar,
               ];
               break;
            case 'center':

                $user_data = [
                    'id' => optional($this->toCenter)->id,
                    'name' => optional($this->toCenter)->name,
                    'avatar' => optional($this->toCenter)->avatar,
               ];
               break;
            default:
               $user_data = [
                 'id' => optional($this->toBabySitter)->id,
                 'name' => optional($this->toBabySitter)->name,
                 'avatar' => optional($this->toBabySitter)->avatar,
               ];
        }
        return $user_data;
        // if($type == 'sitter'){
        //     $user_data = [
        //          'id' => optional($this->toUser)->id,
        //          'name' => optional($this->toUser)->name,
        //          'avatar' => optional($this->toUser)->avatar,
        //     ];
        //  }elseif($type == 'client'){
        //      $user_data = [
        //          'id' => optional($this->toClient)->id,
        //          'name' => optional($this->toClient)->name,
        //          'avatar' => optional($this->toClient)->avatar,
        //     ];
        //  }elseif($type == 'center'){
        //      $user_data = [
        //          'id' => optional($this->toCenter)->id,
        //          'name' => optional($this->toCenter)->name,
        //          'avatar' => optional($this->toCenter)->avatar,
        //     ];
        //  }
        //  elseif($type == 'sitter_worker'){
        //      $user_data = [
        //          'id' => optional($this->toBabySitter)->id,
        //          'name' => optional($this->toBabySitter)->name,
        //          'avatar' => optional($this->toBabySitter)->avatar,
        //     ];
        //  }
        //  return $user_data;

    }
}
