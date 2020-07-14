<?php
class Rectangle
{
    public $width;
    public $height;
    public function __construct($width, $height)
    {    $this->width = $width;
        $this->height = $height;
    }

    public  function calculateArea(){
        $area = $this->height *  $this->width;
        return $area;
    }

}

class Square extends Rectangle
{
    public $width;
    public $height;
    public function __construct($width, $height)
    {   $this->width = $height;
        $this->height = $height;
    }

}

 $r = new Square(10,20);
 $r->calculateArea();