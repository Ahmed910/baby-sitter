<?php

namespace App\Classes;

use App\Interfaces\HourInterface;



class OrderHourCenter implements HourInterface
{
   public function saveOrderByHourService($data,$order)
   {
      $order->hours()->create($data+['order_hoursable_type'=>'App\Models\CenterOrder','order_hoursable_id'=>$order->id]);
   }
}
?>
