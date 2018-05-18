<?php

class AreaCalculator
{
    public function calculate($shape)
    { 
        if ($shape instanceof Rectangle) {
            $area = $shape->width * $shape->height;
        } else {
            $area = $shape->radius * $shape->radius * pi();
        }
       
        return $area;
    }
}