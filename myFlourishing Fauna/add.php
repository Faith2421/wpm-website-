<?php
require_once "pdo.php";
session_start();

// Add Farmer
if (isset($_POST['farmerName']) && isset($_POST['farmName'])
    && isset($_POST['contactNumber']) && isset($_POST['emailAddress'])) {
    try {
        $sql = "INSERT INTO farmers (farmerName, farmName, contactNumber, emailAddress)
                VALUES (:farmerName, :farmName, :contactNumber, :emailAddress)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':farmerName' => $_POST['farmerName'],
            ':farmName' => $_POST['farmName'],
            ':contactNumber' => $_POST['contactNumber'],
            ':emailAddress' => $_POST['emailAddress']
        ));
        $farmerId = $pdo->lastInsertId();
        $_SESSION['farmer_id'] = $farmerId; // Store for later use
        $_SESSION['success'] = 'Farmer Record Added';
        header('Location: home.php');
        return;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error adding farmer: ' . $e->getMessage();
    }
}
?>
<p>Add A New Farmer</p>

<?php
// Add Livestock
if (isset($_POST['animalID']) && isset($_POST['species'])
    && isset($_POST['dateOfBirth']) && isset($_POST['breed']) && isset($_POST['gender'])) {
    try {
        $sql = "INSERT INTO livestock (animalID, species, dateOfBirth, breed, gender)
                VALUES (:animalID, :species, :dateOfBirth, :breed, :gender)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':animalID' => $_POST['animalID'],
            ':species' => $_POST['species'],
            ':dateOfBirth' => $_POST['dateOfBirth'],
            ':breed' => $_POST['breed'],
            ':gender' => $_POST['gender']
        ));
        $_SESSION['success'] = 'Livestock Record Added';
        header('Location: home.php');
        return;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error adding livestock: ' . $e->getMessage();
    }
}
?>
<p>Add New Livestock</p>

<?php
// Add Health Record
if (isset($_POST['checkupDate']) && isset($_POST['veterinarian'])
    && isset($_POST['diagnosis']) && isset($_POST['treatment']) 
    && isset($_POST['healthNotes']) && isset($_POST['nextCheckup'])) {
    try {
        $sql = "INSERT INTO healthRecords (checkupDate, veterinarian, diagnosis, treatment, healthNotes, nextCheckup)
                VALUES (:checkupDate, :veterinarian, :diagnosis, :treatment, :healthNotes, :nextCheckup)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':checkupDate' => $_POST['checkupDate'],
            ':veterinarian' => $_POST['veterinarian'],
            ':diagnosis' => $_POST['diagnosis'],
            ':treatment' => $_POST['treatment'],
            ':healthNotes' => $_POST['healthNotes'],
            ':nextCheckup' => $_POST['nextCheckup']
        ));
        $_SESSION['success'] = 'Health Record Added';
        header('Location: home.php');
        return;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error adding health record: ' . $e->getMessage();
    }
}
?>
<p>Add Health Record</p>

<?php
// Add Breeding Record
if (isset($_POST['breedingDate']) && isset($_POST['sireID'])
    && isset($_POST['expectedBirthDate']) && isset($_POST['pregnancyStatus']) 
    && isset($_POST['breedingNotes'])) {
    try {
        $sql = "INSERT INTO breedingRecords (breedingDate, sireID, expectedBirthDate, pregnancyStatus, breedingNotes)
                VALUES (:breedingDate, :sireID, :expectedBirthDate, :pregnancyStatus, :breedingNotes)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':breedingDate' => $_POST['breedingDate'],
            ':sireID' => $_POST['sireID'],
            ':expectedBirthDate' => $_POST['expectedBirthDate'],
            ':pregnancyStatus' => $_POST['pregnancyStatus'],
            ':breedingNotes' => $_POST['breedingNotes']
        ));
        $_SESSION['success'] = 'Breeding Record Added';
        header('Location: home.php');
        return;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error adding breeding record: ' . $e->getMessage();
    }
}
?>
<p>Add Breeding Record</p>

<?php
// Add Feeding Record
if (isset($_POST['feedType']) && isset($_POST['quantity'])
    && isset($_POST['nutritionalInfo']) && isset($_POST['feedingScheduleNotes'])) {
    try {
        $sql = "INSERT INTO feedingRecords (feedType, quantity, nutritionalInfo, feedingScheduleNotes)
                VALUES (:feedType, :quantity, :nutritionalInfo, :feedingScheduleNotes)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':feedType' => $_POST['feedType'],
            ':quantity' => $_POST['quantity'],
            ':nutritionalInfo' => $_POST['nutritionalInfo'],
            ':feedingScheduleNotes' => $_POST['feedingScheduleNotes']
        ));
        $_SESSION['success'] = 'Feeding Record Added';
        header('Location: home.php');
        return;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error adding feeding record: ' . $e->getMessage();
    }
}
?>
<p>Add Feeding Record</p>

<?php
// Add Vaccination Record
if (isset($_POST['vaccineName']) && isset($_POST['administrationDate'])
    && isset($_POST['nextDueDate']) && isset($_POST['administeredBy']) 
    && isset($_POST['vaccinationNotes'])) {
    try {
        $sql = "INSERT INTO vaccinationRecords (vaccineName, administrationDate, nextDueDate, administeredBy, vaccinationNotes)
                VALUES (:vaccineName, :administrationDate, :nextDueDate, :administeredBy, :vaccinationNotes)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':vaccineName' => $_POST['vaccineName'],
            ':administrationDate' => $_POST['administrationDate'],
            ':nextDueDate' => $_POST['nextDueDate'],
            ':administeredBy' => $_POST['administeredBy'],
            ':vaccinationNotes' => $_POST['vaccinationNotes']
        ));
        $_SESSION['success'] = 'Vaccination Record Added';
        header('Location: home.php');
        return;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error adding vaccination record: ' . $e->getMessage();
    }
}
?>
<p>Add Vaccination Record</p>

<?php
// Add Mortality Record
if (isset($_POST['dateOfDeath']) && isset($_POST['cause'])
    && isset($_POST['disposalMethod']) && isset($_POST['mortalityNotes'])) {
    try {
        $sql = "INSERT INTO mortalityRecords (dateOfDeath, cause, disposalMethod, mortalityNotes)
                VALUES (:dateOfDeath, :cause, :disposalMethod, :mortalityNotes)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':dateOfDeath' => $_POST['dateOfDeath'],
            ':cause' => $_POST['cause'],
            ':disposalMethod' => $_POST['disposalMethod'],
            ':mortalityNotes' => $_POST['mortalityNotes']
        ));
        $_SESSION['success'] = 'Mortality Record Added';
        header('Location: home.php');
        return;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error adding mortality record: ' . $e->getMessage();
    }
}
?>
<p>Add Mortality Record</p>

<?php
// Add Movement Record
if (isset($_POST['movementType']) && isset($_POST['movementDate'])
    && isset($_POST['destination']) && isset($_POST['contactPerson']) 
    && isset($_POST['contactPersonNumber']) && isset($_POST['price']) 
    && isset($_POST['movementNotes'])) {
    try {
        $sql = "INSERT INTO movementRecords (movementType, movementDate, destination, contactPerson, contactPersonNumber, price, movementNotes)
                VALUES (:movementType, :movementDate, :destination, :contactPerson, :contactPersonNumber, :price, :movementNotes)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':movementType' => $_POST['movementType'],
            ':movementDate' => $_POST['movementDate'],
            ':destination' => $_POST['destination'],
            ':contactPerson' => $_POST['contactPerson'],
            ':contactPersonNumber' => $_POST['contactPersonNumber'],
            ':price' => $_POST['price'],
            ':movementNotes' => $_POST['movementNotes']
        ));
        $_SESSION['success'] = 'Movement Record Added';
        header('Location: home.php');
        return;
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error adding movement record: ' . $e->getMessage();
    }
}
?>
<p>Add Movement Record</p>

<?php
// Display success or error messages
if (isset($_SESSION['success'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
    unset($_SESSION['success']);
}
if (isset($_SESSION['error'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
    unset($_SESSION['error']);
}
?>