<?php

class Circle implements  AreaInterface
{
    public  $radius;

    public function __construct($radius)
    {
        $this->radius = $radius;
    }
    
    public  function calculateArea(){
        $area = $this->radius * $this->radius * pi();
        return $area;
    }
}