<?php
require_once 'config.php';

$animalId = isset($_POST['animalID']) ? trim($_POST['animalID']) : '';
$vaccineName = isset($_POST['vaccineName']) ? trim($_POST['vaccineName']) : '';
$administrationDate = isset($_POST['administrationDate']) ? $_POST['administrationDate'] : null;
$nextDueDate = isset($_POST['nextDueDate']) ? $_POST['nextDueDate'] : null;
$administeredBy = isset($_POST['administeredBy']) ? trim($_POST['administeredBy']) : '';
$vaccinationNotes = isset($_POST['vaccinationNotes']) ? trim($_POST['vaccinationNotes']) : '';

try {
    $pdo->beginTransaction();

    if (empty($animalId)) {
        throw new Exception('Animal ID is required.');
    }

    if (empty($vaccineName) || empty($administrationDate)) {
        throw new Exception('Vaccine name and administration date are required.');
    }

    if (!isValidDate($administrationDate)) {
        throw new Exception('Invalid administration date format. Use YYYY-MM-DD.');
    }
    
    if (!empty($nextDueDate) && !isValidDate($nextDueDate)) {
        throw new Exception('Invalid next due date format. Use YYYY-MM-DD.');
    }

    $stmt = $pdo->prepare("INSERT INTO vaccination_records (animal_id, vaccine_name, administration_date, next_due_date, administered_by, notes) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$animalId, $vaccineName, $administrationDate, $nextDueDate, $administeredBy ?: null, $vaccinationNotes ?: null]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Vaccination record added successfully',
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