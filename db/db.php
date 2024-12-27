<?php
class Database
{
    private $connection;

    public function __construct($config)
    {
        $host = "postgresql://{$config['user']}:{$config['password']}@{$config['host']}/{$config['dbname']}?sslmode=require&options=endpoint%3D{$config['endpoint']}";
        $this->connection = pg_connect($host);
        if (!$this->connection) {
            die("Connection failed: " . pg_last_error());
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function closeConnection()
    {
        pg_close($this->connection);
    }
}
