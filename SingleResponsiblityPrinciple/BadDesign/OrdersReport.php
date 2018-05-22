<?php
namespace Demo;

use DB;

class OrdersReport
{
    public function getOrdersInfo($startDate, $endDate)
    {
        $orders = $this->queryDBForOrders($startDate, $endDate);

        return $this->format($orders);
    }


    protected function queryDBForOrders($startDate, $endDate)
    {
        return DB::table('orders')->whereBetween('created_at', [$startDate, $endDate])->get();
    }


    protected function format($orders)
    {
        return '<h1>Orders: ' . $orders . '</h1>';
    }

}