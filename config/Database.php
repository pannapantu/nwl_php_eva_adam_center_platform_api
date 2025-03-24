<?php

class Database
{
    private $host;
    private $databaseName;
    private $user;
    private $password;

    public function __construct($host, $databaseName, $user, $password)
    {
        $this->host = $host;
        $this->databaseName = $databaseName;
        $this->user = $user;
        $this->password = $password;
    }

    public function connection()
    {
        $this->conn = null;
        try {            
            $this->conn = new PDO("sqlsrv:server=" . $this->host . ";Database=" . $this->databaseName, $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Database connected successfully!";
            return $this->conn;
        } catch (PDOException $e) {
            echo "Database connection failed: " . $e->getMessage();
        }

        return $this->conn;
    }
}