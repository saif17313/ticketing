<?php
// Create database for Bus Ticketing System
$host = '127.0.0.1';
$username = 'root';
$password = '';
$database = 'bus_ticketing_bd';

try {
    // Connect to MySQL server
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS $database CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    $conn->exec($sql);
    
    echo "✅ Database '$database' created successfully!\n";
    echo "You can now access it in phpMyAdmin at: http://localhost/phpmyadmin\n";
    
} catch(PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
?>
