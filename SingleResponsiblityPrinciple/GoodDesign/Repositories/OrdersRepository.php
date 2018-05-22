<?php

namespace Report\Repositories;

use DB;

class OrdersRepository
{
    public function getOrdersInfo($startDate, $endDate)
    {
        return DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->get();
    }
}