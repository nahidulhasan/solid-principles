<?php

namespace Demo;

use Auth;
use DB;


class SalesReport
{
    public function between($startDate, $endDate)
    {

        if (! Auth::check()) {
            throw new \Exception('Authentication required for reporting!');
        }

        $sales = $this->queryDBForSales($startDate, $endDate);

        return $this->format($sales);
    }


    protected function queryDBForSales($startDate, $endDate)
    {
        return DB::table('sales')->whereBetween('created_at', [$startDate, $endDate])->sum('charge') / 100;
    }


    protected function format($sales)
    {
        return '<h1>Sales: ' . $sales . '</h1>';
    }

}