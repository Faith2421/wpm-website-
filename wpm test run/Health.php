<?php
require_once 'config.php';

$animalId = isset($_POST['animalID']) ? trim($_POST['animalID']) : '';
$checkupDate = isset($_POST['checkupDate']) ? $_POST['checkupDate'] : null;
$healthNotes = isset($_POST['healthNotes']) ? trim($_POST['healthNotes']) : '';


try {
    $pdo->beginTransaction();

    if (empty($animalId)) {
        throw new Exception('Animal ID is required.');
    }

    if (empty($checkupDate)) {
        throw new Exception('Checkup date is required.');
    }

    if (!isValidDate($checkupDate)) {
        throw new Exception('Invalid checkup date format. Use YYYY-MM-DD.');
    }
    
    if (!empty($nextCheckup) && !isValidDate($nextCheckup)) {
        throw new Exception('Invalid next checkup date format. Use YYYY-MM-DD.');
    }

    $stmt = $pdo->prepare("INSERT INTO health_records (animal_id, checkup_date, veterinarian, diagnosis, treatment, notes, next_checkup) 
        VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$animalId, $checkupDate, $veterinarian ?: null, $diagnosis ?: null, $treatment ?: null, $healthNotes ?: null, $nextCheckup]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Health record added successfully',
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