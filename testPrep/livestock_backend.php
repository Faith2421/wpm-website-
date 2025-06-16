<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "livestock_management"; 

try {
    $pdo = new PDO("mysql:host=$servername:3306;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "connection successful!";// after done testing line remove after testing
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}                                                                    

// Capture and sanitize form data
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

// Validation
$errors = [];

// Validate required fields
if (empty($animalId)) {
    $errors[] = "Animal ID is required";
}

if (empty($species)) {
    $errors[] = "Species is required";
}

// If there are validation errors, return them
if (!empty($errors)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Validation failed',
        'errors' => $errors
    ]);
    exit;
}

try {
    $pdo->beginTransaction();
    
    $farmerId = null;
    $recordsProcessed = [];
    
    // Insert or get farmer
    if (!empty($farmerName) && !empty($farmName)) {
        // Check if farmer exists
        $stmt = $pdo->prepare("SELECT id FROM farmers WHERE name = ? AND farm_name = ? AND email = ?");
        $stmt->execute([$farmerName, $farmName, $emailAddress]);
        $farmer = $stmt->fetch();
        
        if ($farmer) {
            $farmerId = $farmer['id'];
            $recordsProcessed[] = "Farmer found: ID " . $farmerId;
        } else {
            // Insert new farmer (password should be handled properly in production)
            $defaultPassword = password_hash('default123', PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO farmers (name, farm_name, contact_number, email, password) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$farmerName, $farmName, $contactNumber, $emailAddress, $defaultPassword]);
            $farmerId = $pdo->lastInsertId();
            $recordsProcessed[] = "New farmer created: ID " . $farmerId;
        }
    }

    // Insert livestock record
    if ($farmerId && !empty($animalId) && !empty($species)) {
        $stmt = $pdo->prepare("INSERT INTO livestock (farmer_id, animal_id, species, breed, date_of_birth, gender) VALUES (?, ?, ?, ?, ?, ?) " .
                              "ON DUPLICATE KEY UPDATE breed=VALUES(breed), date_of_birth=VALUES(date_of_birth), gender=VALUES(gender)");
        $stmt->execute([$farmerId, $animalId, $species, $breed, $dateOfBirth, $gender]);
        $recordsProcessed[] = "Livestock record processed for Animal ID: " . $animalId;
    }
    
    // Insert health record
    if (!empty($animalId) && !empty($checkupDate)) {
        $stmt = $pdo->prepare("INSERT INTO health_records (animal_id, checkup_date, veterinarian, diagnosis, treatment, notes, next_checkup) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $checkupDate, $veterinarian, $diagnosis, $treatment, $healthNotes, $nextCheckup]);
        $recordsProcessed[] = "Health record added";
    }
    
    // Insert breeding record
    if (!empty($animalId) && !empty($breedingDate)) {
        $stmt = $pdo->prepare("INSERT INTO breeding_records (animal_id, breeding_date, sire_id, expected_birth_date, pregnancy_status, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $breedingDate, $sireId, $expectedBirthDate, $pregnancyStatus, $breedingNotes]);
        $recordsProcessed[] = "Breeding record added";
    }
    
    // Insert feeding record
    if (!empty($animalId) && !empty($feedType)) {
        $recordDate = date('Y-m-d'); // Use current date or accept from form
        $stmt = $pdo->prepare("INSERT INTO feeding_records (animal_id, record_date, feed_type, quantity, nutritional_info, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $recordDate, $feedType, $quantity, $nutritionalInfo, $feedingScheduleNotes]);
        $recordsProcessed[] = "Feeding record added";
    }
    
    // Insert vaccination record
    if (!empty($animalId) && !empty($vaccineName) && !empty($administrationDate)) {
        $stmt = $pdo->prepare("INSERT INTO vaccination_records (animal_id, vaccine_name, administration_date, next_due_date, administered_by, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $vaccineName, $administrationDate, $nextDueDate, $administeredBy, $vaccinationNotes]);
        $recordsProcessed[] = "Vaccination record added";
    }
    
    // Insert mortality record (also update livestock status)
    if (!empty($animalId) && !empty($dateOfDeath)) {
        $stmt = $pdo->prepare("INSERT INTO mortality_records (animal_id, date_of_death, cause_of_death, disposal_method, notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$animalId, $dateOfDeath, $cause, $disposalMethod, $mortalityNotes]);
        
        // Update livestock status to deceased
        $stmt = $pdo->prepare("UPDATE livestock SET status = 'Deceased' WHERE animal_id = ?");
        $stmt->execute([$animalId]);
        $recordsProcessed[] = "Mortality record added and livestock status updated";
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
        $recordsProcessed[] = "Movement record added";
    }
    
    // Final validation before commit
    if (empty($recordsProcessed)) {
        throw new Exception("No records were processed. Please ensure you have provided sufficient data.");
    }
    
    // Verify the animal exists in the database
    $stmt = $pdo->prepare("SELECT animal_id FROM livestock WHERE animal_id = ?");
    $stmt->execute([$animalId]);
    $animalExists = $stmt->fetch();
    
    if (!$animalExists && empty($farmerId)) {
        throw new Exception("No livestock record created and no farmer information provided.");
    }
    
    $pdo->commit();
    
    // Success response with detailed information
    echo json_encode([ 
        'status' => 'success',
        'message' => 'Livestock data submitted successfully!',
        'data' => [
            'farmer_id' => $farmerId ? (int)$farmerId : null,
            'animal_id' => $animalId,
            'records_processed' => $recordsProcessed,
            'timestamp' => date('Y-m-d H:i:s')
        ]
    ]);
    
} catch(Exception $e) {
    $pdo->rollBack();
    
    // Enhanced error response
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to submit livestock data',
        'error_details' => $e->getMessage(),
        'debug_info' => [
            'farmer_id' => $farmerId,
            'animal_id' => $animalId,
            'line' => $e->getLine(),
            'file' => basename($e->getFile())
        ]
    ]);
}
?>