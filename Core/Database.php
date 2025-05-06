<?php

class Database {
    private $host = "localhost";
    private $dbName = "cpesfd";
    private $username = "root";
    private $password = "";
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->dbName}", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // If connection fails, include setupdb.php
            echo "Database connection failed: " . $e->getMessage() . "<br>";
            echo "Running setupdb.php to initialize the database...<br>";
            include __DIR__ . '/setupdb.php'; // Use absolute path
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}
