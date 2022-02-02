<?php

namespace App\Classes;


use App\Interfaces\MonthInterface;

class OrderMonthSitter implements MonthInterface
{
   public function saveOrderByMonthService($data,$order,$month_days,$month_dates)
   {
    $dates = [];
      $order_month = $order->months()->create($data+['order_monthsable_type'=>'App\Models\SitterOrder','order_monthsable_id'=>$order->id]);
      $order_month->month_days()->createMany($month_days);

      if(is_array($month_dates) && count($month_dates) > 0){
        foreach($month_dates as $date){
             $dates[]=['date'=>$date];
        }
    }

    $order_month->month_dates()->createMany($dates);
   }
}
