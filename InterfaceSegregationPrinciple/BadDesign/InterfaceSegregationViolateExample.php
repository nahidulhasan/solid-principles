<?php

interface workerInterface
{
    public  function work();
    public  function  sleep();
}


class HumanWorker implements workerInterface
{
    public  function work()
    {
        var_dump('works');
    }

    public  function  sleep()
    {
        var_dump('sleep');
    }

}

class RobotWorker implements workerInterface
{
    public  function work()
    {
        var_dump('works');
    }

    public  function sleep()
    {
        // No need
    }
}