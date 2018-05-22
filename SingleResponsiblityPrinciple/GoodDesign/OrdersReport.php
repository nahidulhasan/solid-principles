<?php

namespace Report;

use Report\Repositories\OrdersRepository;

class OrdersReport
{
	protected $repo;
	protected $formatter;

	public function __construct(OrdersRepository $repo, OrdersOutPutInterface $formatter)
	{
		$this->repo = $repo;
		$this->formatter = $formatter;
	}

	public function between($startDate, $endDate)
	{
		$orders = $this->repo->getOrdersInfo($startDate, $endDate);

		return $this->formatter->output($orders);
	}
}