<?php
namespace App\Traits;

trait AppProfit
{
    protected function getAppProfit($totol_price_for_order)
    {
        $financial = [];
        $financial['app_profit_percentage'] = (double)setting('app_profit_percentage');
        $financial['app_profit'] = $totol_price_for_order * ($financial['app_profit_percentage']/100);
        $financial['final_price'] = $totol_price_for_order - $financial['app_profit'];
        return $financial;
    }
}
?>
