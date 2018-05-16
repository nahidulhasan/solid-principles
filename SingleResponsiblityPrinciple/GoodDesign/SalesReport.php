<?php

namespace Report;

use Report\Repositories\SalesRepository;

class SalesReport
{
	protected $repo;
	protected $formatter;

	public function __construct(SalesRepository $repo, SalesOutputInterface $formatter)
	{
		$this->repo = $repo;
		$this->formatter = $formatter;
	}

	public function between($startDate, $endDate)
	{
		$sales = $this->repo->between($startDate, $endDate);

		return $this->formatter->output($sales);
	}
}