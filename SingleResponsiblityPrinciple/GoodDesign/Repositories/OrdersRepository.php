<?php

namespace Report\Repositories;

use DB;

class OrdersRepository
{
    public function getOrdersWithDate($startDate, $endDate)
    {
        return DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->get();
    }
}