<?php

namespace App\Classes;

use App\Interfaces\HourInterface;
use App\Models\OrderHour;

class OrderHourSitter implements HourInterface
{
   public function saveOrderByHourService($data,$order)
   {
    $order->hours()->create($data+['order_hoursable_type'=>'App\Models\SitterOrder','order_hoursable_id'=>$order->id]);
   }
}
?>
