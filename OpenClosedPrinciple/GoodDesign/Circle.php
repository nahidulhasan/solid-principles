<?php

class Circle implements  AreaInterface {

    CONST PI = 3.14;
    public  $radius;

    public function __construct($radius)
    {
        $this->radius = $radius;
    }

    public  function calculateArea(){
        $area = Circle::PI * $this->radius * $this->radius;
        return $area;
    }
}