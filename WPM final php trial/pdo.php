<?php
session_start();
$servername = "127.0.0.1";
$username = "root";
$password = ""; // WAMP default
$dbname = "livestock_management";
$pdo = null;
try {
    $pdo = new PDO("mysql:host=$servername;port=3307;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->query("SELECT 1");
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    if (strpos($e->getMessage(), '2002') !== false) {
        $_SESSION['error'] = "Connection failed: MySQL is not running on port 3307. Start WAMP MySQL service.";
    } else {
        $_SESSION['error'] = "Connection failed: " . $e->getMessage() . ". Check WAMP logs at C:\\wamp64\\bin\\mysql\\mysql9.0.1\\data\\<hostname>.err.";
    }
    // Do not redirect here; let the calling page handle it
}
?>