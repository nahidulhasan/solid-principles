<?php

class AreaCalculator
{
    public function calculate($shape)
    {
        $area = 0;
        $area = $shape->calculateArea();
        return $area;
    }
}