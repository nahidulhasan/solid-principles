<?php
/**
 * Depend on Abstractions not on concretions
 */



/*class MySQLConnection{

}


 private $dbConnection;

class PasswordReminder
{
    public  function __construct(MySQLConnection $dbConnection)
    {
       $this->dbConnection =  $dbConnection;
    }

}*/



interface ConnectionInterface
{
    public function connect();
}


class DbConnection implements ConnectionInterface
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

    public  function __construct(ConnectionInterface $dbConnection)
    {
        $this->dbConnection =  $dbConnection;
    }

}