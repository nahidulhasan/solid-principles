<?php

class CostManager
{
    public function calculate($shape)
    {
        $costPerUnit = 1.5;
        if ($shape instanceof Rectangle) {
            $area = $shape->width * $shape->height;
        } else {
            $area = $shape->radius * $shape->radius * pi();
        }
        
        return $costPerUnit * $area;
    }
}

$circle = new Circle(5);
$rect = new Rectangle(8,5);
$obj = new CostManager();
echo $obj->calculate($circle);