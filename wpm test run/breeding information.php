 <?php
require_once 'config.php';

$animalId = isset($_POST['animalID']) ? trim($_POST['animalID']) : '';
$breedingDate = isset($_POST['breedingDate']) ? $_POST['breedingDate'] : null;
$sireId = isset($_POST['sireID']) ? trim($_POST['sireID']) : null;
$expectedBirthDate = isset($_POST['expectedBirthDate']) ? $_POST['expectedBirthDate'] : null;
$pregnancyStatus = isset($_POST['pregnancyStatus']) ? trim($_POST['pregnancyStatus']) : 'Not Confirmed';
$breedingNotes = isset($_POST['breedingNotes']) ? trim($_POST['breedingNotes']) : '';

try {
    $pdo->beginTransaction();

    if (empty($animalId)) {
        throw new Exception('Animal ID is required.');
    }

    if (empty($breedingDate)) {
        throw new Exception('Breeding date is required.');
    }

    if (!isValidDate($breedingDate)) {
        throw new Exception('Invalid breeding date format. Use YYYY-MM-DD.');
    }
    
    if (!empty($expectedBirthDate) && !isValidDate($expectedBirthDate)) {
        throw new Exception('Invalid expected birth date format. Use YYYY-MM-DD.');
    }
    
    if (!in_array($pregnancyStatus, $validPregnancyStatuses)) {
        throw new Exception("Invalid pregnancy status. Must be one of: " . implode(', ', $validPregnancyStatuses));
    }
    
    if ($sireId) {
        $stmt = $pdo->prepare("SELECT animal_id FROM livestock WHERE animal_id = ?");
        $stmt->execute([$sireId]);
        if (!$stmt->fetch()) {
            throw new Exception('Invalid sire ID: not found in livestock.');
        }
    }

    $stmt = $pdo->prepare("INSERT INTO breeding_records (animal_id, breeding_date, sire_id, expected_birth_date, pregnancy_status, notes) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$animalId, $breedingDate, $sireId ?: null, $expectedBirthDate, $pregnancyStatus, $breedingNotes ?: null]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Breeding record added successfully',
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