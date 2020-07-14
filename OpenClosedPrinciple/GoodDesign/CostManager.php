<?php

class CostManager
{
    public function calculate(AreaInterface $shape)
    {
        $costPerUnit = 1.5;
        $totalCost = $costPerUnit * $shape->calculateArea();
        return $totalCost;
    }
}

$circle = new Circle(5);
$obj = new CostManager();
echo $obj->calculate($circle);