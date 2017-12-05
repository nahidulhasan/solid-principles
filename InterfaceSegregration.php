<?php
/**
 * Created by PhpStorm.
 * User: Nahidul Hasan
 * Date: 11-Jul-17
 * Time: 8:56 AM
 */

/*interface workerInterface{
    public  function work();
    public  function  sleep();
}


class HumanWorker implements workerInterface{

    public  function work()
    {
      var_dump('works');
    }
    public  function  sleep()
    {
        var_dump('sleep');
    }

}

class AndroidWorker implements workerInterface{

    public  function work()
    {
        var_dump('works');
    }
    public  function sleep()
    {
       // No need
    }
}*/

interface WorkAbleInterface{
    public  function work();
}

interface SleepAbleInterface{
    public  function  sleep();
}


class HumanWorker implements WorkAbleInterface, SleepAbleInterface{

    public  function work()
    {
        var_dump('works');
    }
    public  function  sleep()
    {
        var_dump('sleep');
    }

}

class AndroidWorker implements WorkAbleInterface{

    public  function work()
    {
        var_dump('works');
    }
    public  function  sleep()
    {
        // No need
    }
}