<?php
// delete_farmer.php
require_once "pdo.php";
session_start();

// Handle Delete Operation farmer
if (isset($_POST['delete']) && isset($_POST['farmer_id'])) {
    try {
        $sql = "DELETE FROM farmers WHERE farmer_id = :farmer_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':farmer_id' => $_POST['farmer_id']));
        $_SESSION['success'] = 'Farmer record deleted successfully';
        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting farmer: ' . $e->getMessage();
        header('Location: home.php');
        exit();
    }
}

// Fetch Farmer Details for Confirmation
if (!isset($_GET['farmer_id'])) {
    $_SESSION['error'] = 'No farmer ID provided';
    header('Location: home.php');
    exit();
}

try {
    $stmt = $pdo->prepare("SELECT farmerName, farmName, contactNumber, emailAddress, farmer_id 
                          FROM farmers WHERE farmer_id = :farmer_id");
    $stmt->execute(array(":farmer_id" => $_GET['farmer_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row === false) {
        $_SESSION['error'] = 'Farmer not found';
        header('Location: home.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header('Location: home.php');
    exit();
}
?>

<?php
// delete_livestock.php
require_once "pdo.php";
session_start();

// Handle delete action livestock
if (isset($_POST['delete']) && isset($_POST['animal_id'])) {
    try {
        $sql = "DELETE FROM livestock WHERE animal_id = :animal_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':animal_id' => $_POST['animal_id']));
        $_SESSION['success'] = 'Animal record deleted';
        header('Location: home.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting animal: ' . $e->getMessage();
        header('Location: home.php');
        exit();
    }
}

// Check if animal_id is provided
if (!isset($_GET['animal_id'])) {
    $_SESSION['error'] = 'No animal ID provided';
    header('Location: home.php');
    exit();
}

// Fetch the animal record to confirm deletion
try {
    $stmt = $pdo->prepare("SELECT animal_id, species, breed FROM livestock WHERE animal_id = :animal_id");
    $stmt->execute(array(":animal_id" => $_GET['animal_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        $_SESSION['error'] = 'Animal not found';
        header('Location: home.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header('Location: home.php');
    exit();
}
?>

<?php
// delete_health_record.php
require_once "pdo.php";
session_start();

// Handle delete action healthRecords
if (isset($_POST['delete']) && isset($_POST['animal_id'])) {
    try {
        $sql = "DELETE FROM health_records WHERE animal_id = :animal_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':animal_id' => $_POST['animal_id']));
        $_SESSION['success'] = 'Health record deleted';
        header('Location: home.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting health record: ' . $e->getMessage();
        header('Location: home.php');
        exit();
    }
}

// Check if animal_id is provided
if (!isset($_GET['animal_id'])) {
    $_SESSION['error'] = 'No animal ID provided';
    header('Location: home.php');
    exit();
}

// Fetch the health record to confirm deletion
try {
    $stmt = $pdo->prepare("SELECT animal_id, species, breed, checkupDate, veterinarian, diagnosis, treatment, healthNotes, nextCheckup 
                          FROM health_records WHERE animal_id = :animal_id");
    $stmt->execute(array(":animal_id" => $_GET['animal_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row === false) {
        $_SESSION['error'] = 'No health record found for this animal ID';
        header('Location: home.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header('Location: home.php');
    exit();
}
?>

<?php
// delete_breeding_record.php
require_once "pdo.php";
session_start();

// Handle delete action breedingRecords
if (isset($_POST['delete']) && isset($_POST['animal_id']) && isset($_POST['breeding_date'])) {
    try {
        $sql = "DELETE FROM breeding_records WHERE animal_id = :animal_id AND breeding_date = :breeding_date";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':animal_id' => $_POST['animal_id'],
            ':breeding_date' => $_POST['breeding_date']
        ));
        $_SESSION['success'] = 'Breeding record deleted';
        header('Location: home.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting breeding record: ' . $e->getMessage();
        header('Location: home.php');
        exit();
    }
}

// Check if required parameters are provided
if (!isset($_GET['animal_id']) || !isset($_GET['breeding_date'])) {
    $_SESSION['error'] = 'Missing required parameters for breeding record';
    header('Location: home.php');
    exit();
}

// Fetch the breeding record to confirm deletion
try {
    $stmt = $pdo->prepare("SELECT animal_id, breeding_date, sire_id, expected_birth_date, pregnancy_status, notes, created_at 
                          FROM breeding_records 
                          WHERE animal_id = :animal_id AND breeding_date = :breeding_date");
    $stmt->execute(array(
        ":animal_id" => $_GET['animal_id'],
        ":breeding_date" => $_GET['breeding_date']
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row === false) {
        $_SESSION['error'] = 'Breeding record not found';
        header('Location: home.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header('Location: home.php');
    exit();
}
?>

<?php
// delete_feeding_record.php
require_once "pdo.php";
session_start();

// Handle delete action feedingRecords
if (isset($_POST['delete']) && isset($_POST['animal_id']) && isset($_POST['record_date']) && isset($_POST['feed_type'])) {
    try {
        $sql = "DELETE FROM feeding_records WHERE animal_id = :animal_id AND record_date = :record_date AND feed_type = :feed_type";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':animal_id' => $_POST['animal_id'], 
            ':record_date' => $_POST['record_date'],
            ':feed_type' => $_POST['feed_type']
        ));
        $_SESSION['success'] = 'Feeding record deleted';
        header('Location: home.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting feeding record: ' . $e->getMessage();
        header('Location: home.php');
        exit();
    }
}

// Check if required parameters are provided
if (!isset($_GET['animal_id']) || !isset($_GET['record_date']) || !isset($_GET['feed_type'])) {
    $_SESSION['error'] = 'Missing required parameters for feeding record';
    header('Location: home.php');
    exit();
}

// Fetch the feeding record to confirm deletion
try {
    $stmt = $pdo->prepare("SELECT animal_id, record_date, feed_type, quantity, nutritional_info, notes 
                          FROM feeding_records 
                          WHERE animal_id = :animal_id AND record_date = :record_date AND feed_type = :feed_type");
    $stmt->execute(array(
        ":animal_id" => $_GET['animal_id'],
        ":record_date" => $_GET['record_date'], 
        ":feed_type" => $_GET['feed_type']
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row === false) {
        $_SESSION['error'] = 'Feeding record not found';
        header('Location: home.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header('Location: home.php');
    exit();
}
?>

<?php
// delete_vaccination_record.php
require_once "pdo.php";
session_start();

// Handle delete action vaccinationRecords
if (isset($_POST['delete']) && isset($_POST['animal_id']) && isset($_POST['vaccine_name']) && isset($_POST['administration_date'])) {
    try {
        $sql = "DELETE FROM vaccination_records WHERE animal_id = :animal_id AND vaccine_name = :vaccine_name AND administration_date = :administration_date";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':animal_id' => $_POST['animal_id'], 
            ':vaccine_name' => $_POST['vaccine_name'],
            ':administration_date' => $_POST['administration_date']
        ));
        $_SESSION['success'] = 'Vaccination record deleted';
        header('Location: home.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting vaccination record: ' . $e->getMessage();
        header('Location: home.php');
        exit();
    }
}

// Check if required parameters are provided
if (!isset($_GET['animal_id']) || !isset($_GET['administration_date']) || !isset($_GET['vaccine_name'])) {
    $_SESSION['error'] = 'Missing required parameters for vaccination record';
    header('Location: home.php');
    exit();
}

// Fetch the vaccination record to confirm deletion
try {
    $stmt = $pdo->prepare("SELECT animal_id, vaccine_name, administration_date, next_due_date, administered_by, notes 
                          FROM vaccination_records 
                          WHERE animal_id = :animal_id AND vaccine_name = :vaccine_name AND administration_date = :administration_date");
    $stmt->execute(array(
        ":animal_id" => $_GET['animal_id'],
        ":vaccine_name" => $_GET['vaccine_name'], 
        ":administration_date" => $_GET['administration_date']
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row === false) {
        $_SESSION['error'] = 'Vaccination record not found';
        header('Location: home.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header('Location: home.php');
    exit();
}
?>

<?php
// delete_mortality_record.php
require_once "pdo.php";
session_start();

// Check if animal_id is provided
if (!isset($_GET['animal_id'])) {
    $_SESSION['error'] = 'No animal ID provided';
    header('Location: home.php');
    exit();
}

// Handle delete action mortality Records
if (isset($_POST['delete']) && isset($_POST['animal_id'])) {
    try {
        $sql = "DELETE FROM mortality_records WHERE animal_id = :animal_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':animal_id' => $_POST['animal_id']));
       
        $_SESSION['success'] = 'Mortality record deleted';
        header('Location: home.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting mortality record: ' . $e->getMessage();
        header('Location: home.php');
        exit();
    }
}

// Fetch the mortality record to confirm deletion
try {
    $stmt = $pdo->prepare("SELECT animal_id, date_of_death, cause_of_death, disposal_method, notes, created_at 
                           FROM mortality_records 
                           WHERE animal_id = :animal_id");
    $stmt->execute(array(":animal_id" => $_GET['animal_id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row === false) {
        $_SESSION['error'] = 'No mortality record found for this animal';
        header('Location: home.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header('Location: home.php');
    exit();
}
?>

<?php
// delete_movement_record.php
require_once "pdo.php";
session_start();

// Check if required parameters are provided
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'No movement record ID provided';
    header('Location: home.php');
    exit();
}

// Handle delete action Movement Records
if (isset($_POST['delete']) && isset($_POST['id'])) {
    try {
        $sql = "DELETE FROM movement_records WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':id' => $_POST['id']));
        
        $_SESSION['success'] = 'Movement record deleted';
        header('Location: home.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['error'] = 'Error deleting movement record: ' . $e->getMessage();
        header('Location: home.php');
        exit();
    }
}

// Fetch the movement record to confirm deletion
try {
    $stmt = $pdo->prepare("SELECT id, animal_id, movement_type, movement_date, destination, contact_person, contact_number, price, notes, created_at 
                           FROM movement_records 
                           WHERE id = :id");
    $stmt->execute(array(":id" => $_GET['id']));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row === false) {
        $_SESSION['error'] = 'No movement record found';
        header('Location: home.php');
        exit();
    }
} catch (PDOException $e) {
    $_SESSION['error'] = 'Database error: ' . $e->getMessage();
    header('Location: home.php');
    exit();
}
?>