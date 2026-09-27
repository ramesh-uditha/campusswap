<?php
/**
 * CampusSwap - Database Connection Manager
 * ICT 2209 Web Technologies Project
 */

$db_host = 'localhost';
$db_name = 'campusswap_db';
$db_user = 'root';
$db_pass = ''; // Default password for XAMPP / WAMP

try {
    $pdo = new PDO("mysql:host={$db_host};dbname={$db_name};charset=utf8mb4", $db_user, $db_pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // Database connection error handling for local development/demonstration
    $db_connection_error = $e->getMessage();
    $pdo = null;
}
