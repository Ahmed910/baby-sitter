<?php

namespace App\Console\Commands;

use App\Models\CenterOrder;
use App\Models\SitterOrder;
use App\Traits\Order;
use Illuminate\Console\Command;

class CancelOrderAfterPassedTime extends Command
{
    use Order;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'passedTime:cancelOrder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sitter_orders = SitterOrder::whereIn('status',['waiting','process'])->get();
        $center_orders = CenterOrder::whereIn('status',['waiting','process'])->get();
        if($sitter_orders->count() > 0){
            foreach($sitter_orders as $sitter_order)
            {
                if(optional($sitter_order->service)->service_type == 'hour'){

                   $sitter_order_hour = $sitter_order->hours()->where('date','<',now()->format('Y-m-d'))->first();
                   if($sitter_order_hour){
                       $sitter_order->update(['status'=>'canceled']);
                       $this->chargeWallet(optional($sitter_order->main_order)->price_after_offer, $sitter_order->client_id);
                   }

                }else{

                     $sitter_order->months->month_dates()->where('status','waiting')->where('date','<',now()->format('Y-m-d'))->update(['status'=>'canceled']);
                    // $sitter_order_month->update(['status'=>'canceled']);
                }
            }
        }
        if($center_orders->count() > 0)
        {
            foreach($center_orders as $center_order)
            {
                if(optional($center_order->service)->service_type == 'hour'){

                   $center_order_hour = $center_order->hours()->where('date','<',now()->format('Y-m-d'))->first();
                   if($center_order_hour){
                       $center_order->update(['status'=>'canceled']);
                       $this->chargeWallet(optional($center_order->main_order)->price_after_offer, $center_order->client_id);
                   }

                }else{

                    $center_order_month = $center_order->months->month_dates()->where('status','waiting')->where('date','<',now()->format('Y-m-d'))->first();
                    $center_order_month->update(['status'=>'canceled']);
                }
            }
        }

    }
}
