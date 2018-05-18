<?php

class Rectangle implements AreaInterface
{

    public $width;
    public $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    public  function calculateArea(){
        $area = $this->height *  $this->width;
        return $area;
    }

}