<?php

 interface area{
     public  function calculateArea();
 }

 class Rectangle implements area{

    public  $height;
    public  $width;

    public function setHeight($height)
    {
      $this->height = $height;
    }

    public function setWidth($width)
    {
        $this->width = $width;
    }

    public  function getHeight()
    {
        return $this->height;
    }

    public function getWidth()
    {
        return $this->width;
    }

     public  function calculateArea(){
         $area = $this->height *  $this->width;
         return $area;
     }
}


class Circle implements  area{
    CONST PI = 3.14;
    public  $radius;

    public function setRadius($radius)
    {
        $this->radius = $radius;
    }

    public  function getRadius()
    {
        return $this->radius;
    }

    public  function calculateArea(){
        $area = Circle::PI * $this->radius * $this->radius;
        return $area;
    }
}

class AreaCalculator{

    public function area($shape)
    {
        $area = 0;
        $area = $shape->calculateArea();
        return $area;
    }

}

$rectObj = new Rectangle();
$rectObj->setHeight(20);
$rectObj->setWidth(15);

$circleObj = new Circle();
$circleObj->setRadius(5);

$calculateObj = new AreaCalculator();
$area = $calculateObj->area($rectObj);
print_r($area);

echo "---";
$area = $calculateObj->area($circleObj);
print_r($area);
