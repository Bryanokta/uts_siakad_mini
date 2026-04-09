<?php
session_start();

class Database
{
    private $host = "localhost";
    private $username = "bryan";
    private $password = "bryan.04";
    private $database = "siakad_mini";
    public $conn;

    public function getConnection()
    {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            echo "Error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
