<?php

namespace App\Console\Commands;

use App\Classes\Statuses;
use App\Models\CenterOrder;
use App\Models\SitterOrder;
use App\Traits\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        DB::beginTransaction();

        try {
            $sitter_orders = SitterOrder::whereIn('status', [Statuses::WAITING, Statuses::PROCESS])->get();
            $center_orders = CenterOrder::whereIn('status', [Statuses::WAITING, Statuses::PROCESS])->get();
            if ($sitter_orders->count() > 0) {
                foreach ($sitter_orders as $sitter_order) {
                    if (optional($sitter_order->service)->service_type == 'hour') {

                        $sitter_order_hour = $sitter_order->hours()->where('date', '<', now()->format('Y-m-d'))->first();
                        if ($sitter_order_hour) {
                            $sitter_order->update(['status' => 'canceled']);
                            $this->chargeWallet(optional($sitter_order->main_order)->price_after_offer, $sitter_order->client_id);
                        }
                    } else {


                        if ($sitter_order->months && $sitter_order->months->month_dates->count() > 0) {
                            // Log::info($sitter_order->months);

                            $sitter_order_month = $sitter_order->months->month_dates()->where('order_month_dates.status','<>', 'completed')->where('order_month_dates.date', '<', now()->format('Y-m-d'))->firstOrFail();
                            // $sitter_order_month->update(['status'=>'canceled']);
                            // Log::info($sitter_order->months->month_dates);

                            $sitter_order_month->update(['status' => 'canceled']);
                            // Log::info($hours*optional($sitter_order->months)->price_per_hour_for_month);
                        }
                    }
                }
            }
            if ($center_orders->count() > 0) {
                foreach ($center_orders as $center_order) {
                    if (optional($center_order->service)->service_type == 'hour') {

                        $center_order_hour = $center_order->hours()->where('date', '<', now()->format('Y-m-d'))->first();
                        if ($center_order_hour) {
                            $center_order->update(['status' => 'canceled']);
                            $this->chargeWallet(optional($center_order->main_order)->price_after_offer, $center_order->client_id);
                        }
                    } else {


                        if ($center_order->months) {

                            $center_order_month = $center_order->months->month_dates()->where('order_month_dates.status','<>', 'completed')->where('order_month_dates.date', '<', now()->format('Y-m-d'))->firstOrFail();

                            $center_order_month->update(['status' => 'canceled']);

                            // Log::info($hours*optional($sitter_order->months)->price_per_hour_for_month);
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            // something went wrong
        }
    }
}
