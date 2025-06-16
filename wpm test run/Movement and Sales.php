<?php
require_once 'config.php';

$animalId = isset($_POST['animalID']) ? trim($_POST['animalID']) : '';
$movementType = isset($_POST['movementType']) ? trim($_POST['movementType']) : '';
$movementDate = isset($_POST['movementDate']) ? $_POST['movementDate'] : null;
$destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';
$contactPerson = isset($_POST['contactPerson']) ? trim($_POST['contactPerson']) : '';
$contactPersonNumber = isset($_POST['contactPersonNumber']) ? trim($_POST['contactPersonNumber']) : '';
$price = isset($_POST['price']) && is_numeric($_POST['price']) ? floatval($_POST['price']) : null;
$movementNotes = isset($_POST['movementNotes']) ? trim($_POST['movementNotes']) : '';

try {
    $pdo->beginTransaction();

    if (empty($animalId)) {
        throw new Exception('Animal ID is required.');
    }

    if (empty($movementType) || empty($movementDate)) {
        throw new Exception('Movement type and date are required.');
    }

    if (!in_array($movementType, $validMovementTypes)) {
        throw new Exception("Invalid movement type. Must be one of: " . implode(', ', $validMovementTypes));
    }
    
    if (!isValidDate($movementDate)) {
        throw new Exception('Invalid movement date format. Use YYYY-MM-DD.');
    }

    $stmt = $pdo->prepare("INSERT INTO movement_records (animal_id, movement_type, movement_date, destination, contact_person, contact_number, price, notes) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$animalId, $movementType, $movementDate, $destination ?: null, $contactPerson ?: null, $contactPersonNumber ?: null, $price, $movementNotes ?: null]);

    $newStatus = 'Active';
    if ($movementType === 'Sale') {
        $newStatus = 'Sold';
    } elseif ($movementType === 'Transfer Out') {
        $newStatus = 'Transferred';
    }

    if ($newStatus !== 'Active') {
        $stmt = $pdo->prepare("UPDATE livestock SET status = ? WHERE animal_id = ?");
        $stmt->execute([$newStatus, $animalId]);
    }

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Movement record added successfully',
        'animal_id' => $animalId
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