<?php

class AreaCalculator{

    public function area($shape)
    {
        $area = 0;
        $area = $shape->calculateArea();
        return $area;
    }

}