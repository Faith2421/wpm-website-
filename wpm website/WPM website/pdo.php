<?php
$host = 'localhost'; // or '127.0.0.1'
$port = '3306';      // Default MySQL port is 3306, not 3307
$dbname = 'livestock_management';
$username = 'root';  // Default WAMP username
$password = '';      // Default WAMP password is empty

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['error'] = "Connection failed: " . $e->getMessage();
    $pdo = null;
}
?>