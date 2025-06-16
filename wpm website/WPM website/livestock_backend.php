<?php
header("Content-Type: application/json");

$config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '', 
    'dbname' => 'livestock_management',
    'port' => 3306, // Changed from 3307 to standard XAMPP port
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];

// Initialize variables for all form fields
$farmerName = $farmName = $contactNumber = $emailAddress = '';
$animalId = $species = $breed = $gender = '';
$dateOfBirth = $checkupDate = $nextCheckup = null;
$veterinarian = $diagnosis = $treatment = $healthNotes = '';
$breedingDate = $sireId = $expectedBirthDate = $breedingNotes = '';
$feedType = $quantity = $nutritionalInfo = $feedingScheduleNotes = '';
$vaccineName = $administrationDate = $nextDueDate = $administeredBy = $vaccinationNotes = '';
$dateOfDeath = $cause = $disposalMethod = $mortalityNotes = '';
$movementType = $movementDate = $destination = $contactPerson = $contactPersonNumber = $movementNotes = '';
$price = null;

// Capture and sanitize form data from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $farmerName = isset($_POST["farmerName"]) ? trim($_POST['farmerName']) : '';
    $farmName = isset($_POST["farmName"]) ? trim($_POST['farmName']) : '';
    $contactNumber = isset($_POST["contactNumber"]) ? trim($_POST['contactNumber']) : '';
    $emailAddress = isset($_POST["emailAddress"]) ? trim($_POST['emailAddress']) : '';
    
    // Livestock Information
    $animalId = isset($_POST["animalID"]) ? trim($_POST['animalID']) : '';
    $species = isset($_POST["species"]) ? $_POST['species'] : '';
    $dateOfBirth = isset($_POST["dateOfBirth"]) ? $_POST['dateOfBirth'] : null;
    $breed = isset($_POST["breed"]) ? trim($_POST['breed']) : '';
    $gender = isset($_POST["gender"]) ? $_POST['gender'] : 'Unknown';
    
    // Health Records
    $checkupDate = isset($_POST["checkupDate"]) ? $_POST['checkupDate'] : null;
    $veterinarian = isset($_POST["veterinarian"]) ? trim($_POST['veterinarian']) : '';
    $diagnosis = isset($_POST["diagnosis"]) ? trim($_POST['diagnosis']) : '';
    $treatment = isset($_POST["treatment"]) ? trim($_POST['treatment']) : '';
    $healthNotes = isset($_POST["healthNotes"]) ? trim($_POST['healthNotes']) : '';
    $nextCheckup = isset($_POST["nextCheckup"]) ? $_POST['nextCheckup'] : null;
    
    // Breeding Information
    $breedingDate = isset($_POST["breedingDate"]) ? $_POST['breedingDate'] : null;
    $sireId = isset($_POST["sireID"]) ? trim($_POST['sireID']) : null;
    $expectedBirthDate = isset($_POST["expectedBirthDate"]) ? $_POST['expectedBirthDate'] : null;
    $pregnancyStatus = isset($_POST["pregnancyStatus"]) ? $_POST['pregnancyStatus'] : 'Not Confirmed';
    $breedingNotes = isset($_POST["breedingNotes"]) ? trim($_POST['breedingNotes']) : '';
    
    // Feeding & Nutrients
    $feedType = isset($_POST["feedType"]) ? trim($_POST['feedType']) : '';
    $quantity = isset($_POST["quantity"]) ? trim($_POST['quantity']) : '';
    $nutritionalInfo = isset($_POST["nutritionalInfo"]) ? trim($_POST['nutritionalInfo']) : '';
    $feedingScheduleNotes = isset($_POST["feedingScheduleNotes"]) ? trim($_POST['feedingScheduleNotes']) : '';
    
    // Vaccination Records
    $vaccineName = isset($_POST["vaccineName"]) ? trim($_POST['vaccineName']) : '';
    $administrationDate = isset($_POST["administrationDate"]) ? $_POST['administrationDate'] : null;
    $nextDueDate = isset($_POST["nextDueDate"]) ? $_POST['nextDueDate'] : null;
    $administeredBy = isset($_POST["administeredBy"]) ? trim($_POST['administeredBy']) : '';
    $vaccinationNotes = isset($_POST["vaccinationNotes"]) ? trim($_POST['vaccinationNotes']) : '';
    
    // Mortality Records
    $dateOfDeath = isset($_POST["dateOfDeath"]) ? $_POST['dateOfDeath'] : null;
    $cause = isset($_POST["cause"]) ? trim($_POST['cause']) : '';
    $disposalMethod = isset($_POST["disposalMethod"]) ? trim($_POST['disposalMethod']) : '';
    $mortalityNotes = isset($_POST["mortalityNotes"]) ? trim($_POST['mortalityNotes']) : '';
    
    // Movement & Sales Records
    $movementType = isset($_POST["movementType"]) ? $_POST['movementType'] : '';
    $movementDate = isset($_POST["movementDate"]) ? $_POST['movementDate'] : null;
    $destination = isset($_POST["destination"]) ? trim($_POST['destination']) : '';
    $contactPerson = isset($_POST["contactPerson"]) ? trim($_POST['contactPerson']) : '';
    $contactPersonNumber = isset($_POST["contactPersonNumber"]) ? trim($_POST['contactPersonNumber']) : '';
    $price = isset($_POST["price"]) ? floatval($_POST['price']) : null;
    $movementNotes = isset($_POST["movementNotes"]) ? trim($_POST['movementNotes']) : '';
}

try {
    // Establish connection
    $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['dbname']};charset=utf8mb4";
    $pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
    
    // Test connection
    $pdo->query("SELECT 1");
    
    $pdo->beginTransaction();
    
    // Insert or get farmer
    $farmerId = null;
    if (!empty($farmerName) && !empty($farmName)) {
        // Check if farmer exists
        $stmt = $pdo->prepare("SELECT id FROM farmers WHERE email = ? OR (name = ? AND farm_name = ?)");
        $stmt->execute([$emailAddress, $farmerName, $farmName]);
        $farmer = $stmt->fetch();
        
        if ($farmer) {
            $farmerId = $farmer['id'];
        } else {
            // Insert new farmer (password should be handled properly in production)
            $defaultPassword = password_hash('default123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO farmers (name, farm_name, contact_number, email, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$farmerName, $farmName, $contactNumber, $emailAddress, $defaultPassword]);
            $farmerId = $pdo->lastInsertId();
        }
    }
    
    // Insert livestock record
    if ($farmerId && !empty($animalId) && !empty($species)) {
        $stmt = $pdo->prepare("INSERT INTO livestock (farmer_id, animal_id, species, breed, date_of_birth, gender) VALUES (?, ?, ?, ?, ?, ?) 
                              ON DUPLICATE KEY UPDATE breed=VALUES(breed), date_of_birth=VALUES(date_of_birth), gender=VALUES(gender)");
        $stmt->execute([$farmerId, $animalId, $species, $breed, $dateOfBirth, $gender]);
    }
    
    // Insert health record
    if (!empty($animalId) && !empty($checkupDate)) {
        $stmt = $pdo->prepare("INSERT INTO health_records (animal_id, checkup_date, veterinarian, diagnosis, treatment, notes, next_checkup) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $checkupDate, $veterinarian, $diagnosis, $treatment, $healthNotes, $nextCheckup]);
    }
    
    // Insert breeding record
    if (!empty($animalId) && !empty($breedingDate)) {
        $stmt = $pdo->prepare("INSERT INTO breeding_records (animal_id, breeding_date, sire_id, expected_birth_date, pregnancy_status, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $breedingDate, $sireId, $expectedBirthDate, $pregnancyStatus, $breedingNotes]);
    }
    
    // Insert feeding record
    if (!empty($animalId) && !empty($feedType)) {
        $recordDate = date('Y-m-d'); // Use current date or accept from form
        $stmt = $pdo->prepare("INSERT INTO feeding_records (animal_id, record_date, feed_type, quantity, nutritional_info, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $recordDate, $feedType, $quantity, $nutritionalInfo, $feedingScheduleNotes]);
    }
    
    
    // Insert mortality record (also update livestock status)
    if (!empty($animalId) && !empty($dateOfDeath)) {
        $stmt = $pdo->prepare("INSERT INTO mortality_records (animal_id, date_of_death, cause_of_death, disposal_method, notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $dateOfDeath, $cause, $disposalMethod, $mortalityNotes]);
        
        // Update livestock status to deceased
        $stmt = $pdo->prepare("UPDATE livestock SET status = 'Deceased' WHERE animal_id = ?");
        $stmt->execute([$animalId]);
    }
    
    // Insert movement record (also update livestock status if sold/transferred)
    if (!empty($animalId) && !empty($movementType) && !empty($movementDate)) {
        $stmt = $pdo->prepare("INSERT INTO movement_records (animal_id, movement_type, movement_date, destination, contact_person, contact_number, price, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $movementType, $movementDate, $destination, $contactPerson, $contactPersonNumber, $price, $movementNotes]);
        
        // Update livestock status based on movement type
        $newStatus = 'Active';
        if ($movementType === 'Sale') $newStatus = 'Sold';
        elseif (in_array($movementType, ['Transfer Out'])) $newStatus = 'Transferred';
        
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
        'animal_id' => $animalId
    ]);
    
} catch(PDOException $e) {
    if (isset($pdo) && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error',
        'details' => $e->getMessage(),
        'solution' => 'Please check database configuration and ensure MySQL is running'
    ]);
} catch(Exception $e) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Application error',
        'details' => $e->getMessage()
    ]);
    exit;
}