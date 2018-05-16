<?php

namespace Acme\Repositories;

use DB;

class SalesRepository
{
    protected function between($startDate, $endDate)
    {
        return DB::table('sales')->whereBetween('created_at', [$startDate, $endDate])->sum('charge') / 100;
    }
}