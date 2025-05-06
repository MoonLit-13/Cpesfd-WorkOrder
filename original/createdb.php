<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'my_project_db'; 

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $pdo->exec($sql);

    echo "Database '$dbname' created successfully.";
} catch (PDOException $e) {
    echo "Database creation failed: " . $e->getMessage();
}
?>