<?php

namespace App\Console\Commands;

use App\Models\CenterOrder;
use App\Models\MainOrder;
use App\Models\SitterOrder;
use App\Traits\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CancelOrder extends Command
{
    use Order;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cancel:order';

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
            $sitter_orders = SitterOrder::where('status', 'pending')->get();

            $center_orders = CenterOrder::where('status', 'pending')->get();

            if ($sitter_orders->count() > 0) {
                foreach ($sitter_orders as $sitter_order) {
                    if ($sitter_order->created_at->addHours(12)->format('Y-m-d H:i:s') < now()->format('Y-m-d H:i:s')) {
                        $sitter_order->update(['status' => 'canceled']);
                        $this->chargeWallet(optional($sitter_order->main_order)->price_after_offer, $sitter_order->client_id);
                    }
                }
            }
            if ($center_orders->count() > 0) {
                foreach ($center_orders as $center_order) {
                    if ($center_order->created_at->addHours(12)->format('Y-m-d H:i:s') < now()->format('Y-m-d H:i:s')) {
                        $center_order->update(['status' => 'canceled']);
                        $this->chargeWallet(optional($center_order->main_order)->price_after_offer, $center_order->client_id);
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
