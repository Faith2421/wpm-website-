<?php
session_start();
require_once "pdo.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['farmer_id']) && !empty($_POST['farmer_id'])) {
            $stmt = $pdo->prepare("DELETE FROM farmers WHERE id = ?");
            $stmt->execute([$_POST['farmer_id']]);
            $_SESSION['success'] = "Farmer deleted successfully.";
        } elseif (isset($_POST['animal_id']) && !empty($_POST['animal_id'])) {
            $stmt = $pdo->prepare("DELETE FROM livestock WHERE animal_id = ?");
            $stmt->execute([$_POST['animal_id']]);
            $_SESSION['success'] = "Livestock deleted successfully.";
        } elseif (isset($_POST['health_animal_id']) && isset($_POST['health_date']) && !empty($_POST['health_animal_id']) && !empty($_POST['health_date'])) {
            $stmt = $pdo->prepare("DELETE FROM health_records WHERE animal_id = ? AND checkup_date = ?");
            $stmt->execute([$_POST['health_animal_id'], $_POST['health_date']]);
            $_SESSION['success'] = "Health record deleted successfully.";
        } elseif (isset($_POST['breeding_animal_id']) && isset($_POST['breeding_date']) && !empty($_POST['breeding_animal_id']) && !empty($_POST['breeding_date'])) {
            $stmt = $pdo->prepare("DELETE FROM breeding_records WHERE animal_id = ? AND breeding_date = ?");
            $stmt->execute([$_POST['breeding_animal_id'], $_POST['breeding_date']]);
            $_SESSION['success'] = "Breeding record deleted successfully.";
        } elseif (isset($_POST['feeding_animal_id']) && isset($_POST['feeding_date']) && isset($_POST['feeding_type']) && !empty($_POST['feeding_animal_id']) && !empty($_POST['feeding_date']) && !empty($_POST['feeding_type'])) {
            $stmt = $pdo->prepare("DELETE FROM feeding_records WHERE animal_id = ? AND record_date = ? AND feed_type = ?");
            $stmt->execute([$_POST['feeding_animal_id'], $_POST['feeding_date'], $_POST['feeding_type']]);
            $_SESSION['success'] = "Feeding record deleted successfully.";
        } elseif (isset($_POST['vaccination_animal_id']) && isset($_POST['vaccination_vaccine_name']) && isset($_POST['vaccination_date']) && !empty($_POST['vaccination_animal_id']) && !empty($_POST['vaccination_vaccine_name']) && !empty($_POST['vaccination_date'])) {
            $stmt = $pdo->prepare("DELETE FROM vaccination_records WHERE animal_id = ? AND vaccine_name = ? AND administration_date = ?");
            $stmt->execute([$_POST['vaccination_animal_id'], $_POST['vaccination_vaccine_name'], $_POST['vaccination_date']]);
            $_SESSION['success'] = "Vaccination record deleted successfully.";
        } elseif (isset($_POST['mortality_animal_id']) && isset($_POST['mortality_date']) && !empty($_POST['mortality_animal_id']) && !empty($_POST['mortality_date'])) {
            $stmt = $pdo->prepare("DELETE FROM mortality_records WHERE animal_id = ? AND date_of_death = ?");
            $stmt->execute([$_POST['mortality_animal_id'], $_POST['mortality_date']]);
            $_SESSION['success'] = "Mortality record deleted successfully.";
        } elseif (isset($_POST['movement_id']) && !empty($_POST['movement_id'])) {
            $stmt = $pdo->prepare("DELETE FROM movement_records WHERE id = ?");
            $stmt->execute([$_POST['movement_id']]);
            $_SESSION['success'] = "Movement record deleted successfully.";
        } else {
            $_SESSION['error'] = "Invalid deletion request. Required parameters missing.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting record: " . $e->getMessage();
    }
    header("Location: indexx.php");
    exit();
}

// No POST data, redirect safely
header("Location: indexx.php");
exit();
?>