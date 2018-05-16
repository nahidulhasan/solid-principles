<?php

class AreaCalculator
{
    public function calculate($shapes)
    {
        foreach ($shapes as $shape) {

            if ($shape instanceof Rectangle) {
                $areas[] = $shape->width * $shape->height;
            } else {
                $areas[] = $shape->radius ** 2 * pi();
            }
        }

        return array_sum($areas);
    }
}