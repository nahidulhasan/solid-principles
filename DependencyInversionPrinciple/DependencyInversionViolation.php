<?php

class MySQLConnection
{
   /**
   * db connection
   */
   public function connect()
   {
      var_dump('MYSQL Connection');
   }
}


class PasswordReminder
{    
    /**
     * @var MySQLConnection
     */
     private $dbConnection;
     

    public function __construct(MySQLConnection $dbConnection) 
    {
      $this->dbConnection = $dbConnection;
    }

}