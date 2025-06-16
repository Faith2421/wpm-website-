<?php
// Database configuration
$config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'dbname' => 'livestock_management',
    'port' => 3307,
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];

// Initialize database connection
try {
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    $pdo->query("SELECT 1"); // Test connection
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed',
        'details' => 'Unable to connect to the database'
    ]);
    exit;
}

// Allowed enum values from the schema
$validSpecies = ['Cattle', 'Goat', 'Sheep', 'Pig', 'Poultry', 'Other'];
$validGenders = ['Male', 'Female', 'Unknown'];
$validPregnancyStatuses = ['Confirmed', 'Not Confirmed', 'Failed'];
$validMovementTypes = ['Sale', 'Purchase', 'Transfer In', 'Transfer Out', 'Show', 'Other'];

// Helper function to validate date format (YYYY-MM-DD)
function isValidDate($date) {
    if (empty($date)) return true; // Allow null dates
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Helper function to generate a secure random password
function generateRandomPassword($length = 12) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}
?>