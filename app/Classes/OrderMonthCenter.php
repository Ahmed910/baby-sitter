<?php

namespace App\Classes;

use App\Interfaces\MonthInterface;
use App\Models\OrderMonthDate;
use App\Models\OrderMonthDay;

class OrderMonthCenter implements MonthInterface
{
   public function saveOrderByMonthService($data,$order,$month_days)
   {

    $order_month = $order->months()->create($data+['order_monthsable_type'=>'App\Models\CenterOrder','order_monthsable_id'=>$order->id]);
    foreach ($month_days as $month_day) {
        //  dd(array_except($month_day, ['date'])+['order_month_id'=>$order_month->id]);

        $day = OrderMonthDay::create(array_except($month_day, ['date'])+['order_month_id'=>$order_month->id]);
        foreach($month_day['date'] as $order_month_date){

            $arr[]=['date'=>$order_month_date,'order_month_id'=>$order_month->id];
            OrderMonthDate::create(['date'=>$order_month_date,'order_month_day_id'=>$day->id,'order_month_id'=>$order_month->id]);
        }
        // $day->month_dates()->createMany($arr);

        // dd($month_dates);
      }


   }
}
