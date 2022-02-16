<?php

namespace App\Classes;

abstract class Statuses
{
    const PENDING='pending';
    const WAITING='waiting';
    const PROCESS='process';
    const COMPLETED='completed';
    const WITHTHECHILD='with_the_child';
    const CANCELED = 'canceled';
    const HOUR_SERVICE = 1;
    const MONTH_SERVICE = 2;
}
?>
