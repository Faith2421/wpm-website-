<?php
require_once 'config.php';

$animalId = isset($_POST['animalID']) ? trim($_POST['animalID']) : '';
$species = isset($_POST['species']) ? trim($_POST['species']) : '';
$dateOfBirth = isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : null;
$breed = isset($_POST['breed']) ? trim($_POST['breed']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : 'Unknown';


try {
    $pdo->beginTransaction();

    if (!$animalId) {
        throw new Exception('animal ID is required.');
    }

    if (empty($animalId) || empty($species)) {
        throw new Exception('Animal ID and species are required.');
    }

    if (!in_array($species, $validSpecies)) {
        throw new Exception("Invalid species. Must be one of: " . implode(', ', $validSpecies));
    }
    
    if (!in_array($gender, $validGenders)) {
        throw new Exception("Invalid gender. Must be one of: " . implode(', ', $validGenders));
    }
    
    if (!empty($dateOfBirth) && !isValidDate($dateOfBirth)) {
        throw new Exception('Invalid date of birth format. Use YYYY-MM-DD.');
    }
    
    if (strlen($animalId) > 50 || strlen($breed) > 50) {
        throw new Exception('Animal ID or breed exceeds maximum length of 50 characters.');
    }

    $stmt = $pdo->prepare("INSERT INTO livestock (animal_id, farmer_id, species, breed, date_of_birth, gender, status) 
        VALUES (?, ?, ?, ?, ?, ?, 'Active') 
        ON DUPLICATE KEY UPDATE breed=VALUES(breed), date_of_birth=VALUES(date_of_birth), gender=VALUES(gender)");
    $stmt->execute([$animalId, $species, $breed ?: null, $dateOfBirth, $gender]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Livestock record processed successfully',
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