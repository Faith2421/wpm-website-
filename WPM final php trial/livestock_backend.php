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

// Capture and sanitize form data
$farmerName = isset($_POST['farmerName']) ? trim($_POST['farmerName']) : '';
$farmName = isset($_POST['farmName']) ? trim($_POST['farmName']) : '';
$contactNumber = isset($_POST['contactNumber']) ? trim($_POST['contactNumber']) : '';
$emailAddress = isset($_POST['emailAddress']) ? trim($_POST['emailAddress']) : '';

$animalId = isset($_POST['animalID']) ? trim($_POST['animalID']) : '';
$species = isset($_POST['species']) ? trim($_POST['species']) : '';
$dateOfBirth = isset($_POST['dateOfBirth']) ? $_POST['dateOfBirth'] : null;
$breed = isset($_POST['breed']) ? trim($_POST['breed']) : '';
$gender = isset($_POST['gender']) ? trim($_POST['gender']) : 'Unknown';

$checkupDate = isset($_POST['checkupDate']) ? $_POST['checkupDate'] : null;
$veterinarian = isset($_POST['veterinarian']) ? trim($_POST['veterinarian']) : '';
$diagnosis = isset($_POST['diagnosis']) ? trim($_POST['diagnosis']) : '';
$treatment = isset($_POST['treatment']) ? trim($_POST['treatment']) : '';
$healthNotes = isset($_POST['healthNotes']) ? trim($_POST['healthNotes']) : '';
$nextCheckup = isset($_POST['nextCheckup']) ? $_POST['nextCheckup'] : null;

$breedingDate = isset($_POST['breedingDate']) ? $_POST['breedingDate'] : null;
$sireId = isset($_POST['sireID']) ? trim($_POST['sireID']) : null;
$expectedBirthDate = isset($_POST['expectedBirthDate']) ? $_POST['expectedBirthDate'] : null;
$pregnancyStatus = isset($_POST['pregnancyStatus']) ? trim($_POST['pregnancyStatus']) : 'Not Confirmed';
$breedingNotes = isset($_POST['breedingNotes']) ? trim($_POST['breedingNotes']) : '';

$feedType = isset($_POST['feedType']) ? trim($_POST['feedType']) : '';
$quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
$nutritionalInfo = isset($_POST['nutritionalInfo']) ? trim($_POST['nutritionalInfo']) : '';
$feedingRecordDate = isset($_POST['feedingRecordDate']) ? $_POST['feedingRecordDate'] : date('Y-m-d');
$feedingScheduleNotes = isset($_POST['feedingScheduleNotes']) ? trim($_POST['feedingScheduleNotes']) : '';

$vaccineName = isset($_POST['vaccineName']) ? trim($_POST['vaccineName']) : '';
$administrationDate = isset($_POST['administrationDate']) ? $_POST['administrationDate'] : null;
$nextDueDate = isset($_POST['nextDueDate']) ? $_POST['nextDueDate'] : null;
$administeredBy = isset($_POST['administeredBy']) ? trim($_POST['administeredBy']) : '';
$vaccinationNotes = isset($_POST['vaccinationNotes']) ? trim($_POST['vaccinationNotes']) : '';

$dateOfDeath = isset($_POST['dateOfDeath']) ? $_POST['dateOfDeath'] : null;
$cause = isset($_POST['cause']) ? trim($_POST['cause']) : '';
$disposalMethod = isset($_POST['disposalMethod']) ? trim($_POST['disposalMethod']) : '';
$mortalityNotes = isset($_POST['mortalityNotes']) ? trim($_POST['mortalityNotes']) : '';

$movementType = isset($_POST['movementType']) ? trim($_POST['movementType']) : '';
$movementDate = isset($_POST['movementDate']) ? $_POST['movementDate'] : null;
$destination = isset($_POST['destination']) ? trim($_POST['destination']) : '';
$contactPerson = isset($_POST['contactPerson']) ? trim($_POST['contactPerson']) : '';
$contactPersonNumber = isset($_POST['contactPersonNumber']) ? trim($_POST['contactPersonNumber']) : '';
$price = isset($_POST['price']) && is_numeric($_POST['price']) ? floatval($_POST['price']) : null;
$movementNotes = isset($_POST['movementNotes']) ? trim($_POST['movementNotes']) : '';

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
    if (!empty($farmerName) && !empty($farmName)) {
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
    }

    // Validate and insert livestock data
    if ($farmerId && !empty($animalId) && !empty($species)) {
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
        $stmt->execute([$animalId, $farmerId, $species, $breed ?: null, $dateOfBirth, $gender]);
    }

    // Insert health_records
    if (!empty($animalId) && !empty($checkupDate)) {
        if (!isValidDate($checkupDate)) {
            throw new Exception('Invalid checkup date format. Use YYYY-MM-DD.');
        }
        if (!empty($nextCheckup) && !isValidDate($nextCheckup)) {
            throw new Exception('Invalid next checkup date format. Use YYYY-MM-DD.');
        }
        $stmt = $pdo->prepare("INSERT INTO health_records (animal_id, checkup_date, veterinarian, diagnosis, treatment, notes, next_checkup) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $checkupDate, $veterinarian ?: null, $diagnosis ?: null, $treatment ?: null, $healthNotes ?: null, $nextCheckup]);
    }

    // Insert breeding_records
    if (!empty($animalId) && !empty($breedingDate)) {
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
    }

    // Insert feeding_records
    if (!empty($animalId) && !empty($feedType)) {
        if (!isValidDate($feedingRecordDate)) {
            throw new Exception('Invalid feeding record date format. Use YYYY-MM-DD.');
        }
        $stmt = $pdo->prepare("INSERT INTO feeding_records (animal_id, record_date, feed_type, quantity, nutritional_info, notes) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $feedingRecordDate, $feedType, $quantity ?: null, $nutritionalInfo ?: null, $feedingScheduleNotes ?: null]);
    }

    // Insert vaccination_records
    if (!empty($animalId) && !empty($vaccineName) && !empty($administrationDate)) {
        if (!isValidDate($administrationDate)) {
            throw new Exception('Invalid administration date format. Use YYYY-MM-DD.');
        }
        if (!empty($nextDueDate) && !isValidDate($nextDueDate)) {
            throw new Exception('Invalid next due date format. Use YYYY-MM-DD.');
        }
        $stmt = $pdo->prepare("INSERT INTO vaccination_records (animal_id, vaccine_name, administration_date, next_due_date, administered_by, notes) 
            VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $vaccineName, $administrationDate, $nextDueDate, $administeredBy ?: null, $vaccinationNotes ?: null]);
    }

    // Insert mortality_records
    if (!empty($animalId) && !empty($dateOfDeath)) {
        if (!isValidDate($dateOfDeath)) {
            throw new Exception('Invalid date of death format. Use YYYY-MM-DD.');
        }
        $stmt = $pdo->prepare("INSERT INTO mortality_records (animal_id, date_of_death, cause_of_death, disposal_method, notes) 
            VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $dateOfDeath, $cause ?: null, $disposalMethod ?: null, $mortalityNotes ?: null]);

        $stmt = $pdo->prepare("UPDATE livestock SET status = 'Deceased' WHERE animal_id = ?");
        $stmt->execute([$animalId]);
    }

    // Insert movement_records
    if (!empty($animalId) && !empty($movementType) && !empty($movementDate)) {
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
    }

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Livestock data submitted successfully!',
        'farmer_id' => $farmerId,
        'animal_id' => $animalId,
        'inserted_records' => array_filter([
            'farmer' => !empty($farmerName) && !empty($farmName) && $farmerId ? true : null,
            'livestock' => !empty($animalId) && !empty($species) ? true : null,
            'health' => !empty($animalId) && !empty($checkupDate) ? true : null,
            'breeding' => !empty($animalId) && !empty($breedingDate) ? true : null,
            'feeding' => !empty($animalId) && !empty($feedType) ? true : null,
            'vaccination' => !empty($animalId) && !empty($vaccineName) && !empty($administrationDate) ? true : null,
            'mortality' => !empty($animalId) && !empty($dateOfDeath) ? true : null,
            'movement' => !empty($animalId) && !empty($movementType) && !empty($movementDate) ? true : null,
        ])
    ]);

} catch (PDOException $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $errorMessage = 'Database error';
    if (strpos($e->getMessage(), 'FOREIGN KEY')) {
        $errorMessage = 'Invalid reference: Ensure animal ID or sire ID exists in the livestock table.';
    } elseif (strpos($e->getMessage(), 'Duplicate entry')) {
        $errorMessage = 'Duplicate entry: Record already exists for the provided key.';
    }

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $errorMessage,
        'details' => $e->getMessage()
    ]);
} catch (Exception $e) {
    if ($pdo && $pdo->inTransaction()) {
        $pdo->rollBack();
    }

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