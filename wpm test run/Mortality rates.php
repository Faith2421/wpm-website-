<?php
require_once 'config.php';

$animalId = isset($_POST['animalID']) ? trim($_POST['animalID']) : '';
$dateOfDeath = isset($_POST['dateOfDeath']) ? $_POST['dateOfDeath'] : null;
$cause = isset($_POST['cause']) ? trim($_POST['cause']) : '';
$disposalMethod = isset($_POST['disposalMethod']) ? trim($_POST['disposalMethod']) : '';
$mortalityNotes = isset($_POST['mortalityNotes']) ? trim($_POST['mortalityNotes']) : '';

try {
    $pdo->beginTransaction();

    if (empty($animalId)) {
        throw new Exception('Animal ID is required.');
    }

    if (empty($dateOfDeath)) {
        throw new Exception('Date of death is required.');
    }

    if (!isValidDate($dateOfDeath)) {
        throw new Exception('Invalid date of death format. Use YYYY-MM-DD.');
    }

    $stmt = $pdo->prepare("INSERT INTO mortality_records (animal_id, date_of_death, cause_of_death, disposal_method, notes) 
        VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$animalId, $dateOfDeath, $cause ?: null, $disposalMethod ?: null, $mortalityNotes ?: null]);

    $stmt = $pdo->prepare("UPDATE livestock SET status = 'Deceased' WHERE animal_id = ?");
    $stmt->execute([$animalId]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Mortality record added successfully',
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