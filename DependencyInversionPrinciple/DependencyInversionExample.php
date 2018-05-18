<?php

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