<?php

namespace App\Classes;

use App\Interfaces\MonthInterface;

class OrderMonthCenter implements MonthInterface
{
   public function saveOrderByMonthService($data,$order,$month_days)
   {
    $order_month = $order->months()->create($data+['order_monthsable_type'=>'App\Models\CenterOrder','order_monthsable_id'=>$order->id]);
    $order_month->month_days()->createMany($month_days);
   }
}
?>
