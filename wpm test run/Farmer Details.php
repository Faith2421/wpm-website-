 <?php
require_once 'config.php';

// Capture and sanitize form data
$farmerName = isset($_POST['farmerName']) ? trim($_POST['farmerName']) : '';
$farmName = isset($_POST['farmName']) ? trim($_POST['farmName']) : '';
$contactNumber = isset($_POST['contactNumber']) ? trim($_POST['contactNumber']) : '';
$emailAddress = isset($_POST['emailAddress']) ? trim($_POST['emailAddress']) : '';

try {
    $pdo->beginTransaction();

    // Validate required fields for farmers
    if (empty($farmerName) || empty($farmName)) {
        throw new Exception('Farmer name and farm name are required.');
    }

    // Validate email format if provided
    if (!empty($emailAddress) && !filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email format.');
    }

    // Check or create farmer
    $farmerId = null;
    $stmt = $pdo->prepare("SELECT id FROM farmers WHERE email = ? OR (name = ? AND farm_name = ?)");
    $stmt->execute([$emailAddress, $farmerName, $farmName]);
    $farmer = $stmt->fetch();

    if ($farmer) {
        $farmerId = $farmer['id'];
    } else {
        $password = password_hash(generateRandomPassword(), PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO farmers (name, farm_name, contact_number, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$farmerName, $farmName, $contactNumber ?: null, $emailAddress ?: null, $password]);
        $farmerId = $pdo->lastInsertId();
    }

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Farmer data processed successfully',
        'farmer_id' => $farmerId
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error',
        'details' => $e->getMessage()
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Validation error',
        'details' => $e->getMessage()
    ]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back to Home</title>
    <style>
        .back-link {
            display: inline-block;
            padding: 5px 10px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <a href="indexx.php" class="back-link">← Back to Dashboard</a>
</body>
</html>