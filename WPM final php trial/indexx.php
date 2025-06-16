<?php
require_once "pdo.php";

if ($pdo === null) {
    if (isset($_SESSION['error'])) {
        echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n";
        unset($_SESSION['error']);
    }
    exit; // Stop execution if no database connection
}

// Display success or error messages
if (isset($_SESSION['error'])) {
    echo '<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n";
    unset($_SESSION['error']);
}
if (isset($_SESSION['success'])) {
    echo '<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n";
    unset($_SESSION['success']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Livestock Management System</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: #0066cc; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .error { color: red; }
        .success { color: green; }
        form { display: inline; }
    </style>
</head>
<body>
     <a href="Home.php" class="back-link">← Back to Home page</a>
<?php
// Debug: Log successful connection
error_log("Connected to database successfully.");

try {
    // Farmers Table
    echo "<h2>Farmers</h2>";
    echo "<table border='1'><tr><th>Farmer Name</th><th>Farm Name</th><th>Contact Number</th><th>Email</th><th>Actions</th></tr>";
    $stmt = $pdo->query("SELECT name AS farmerName, farm_name AS farmName, contact_number AS contactNumber, email AS emailAddress, id FROM farmers");
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlentities($row['farmerName']) . "</td>";
        echo "<td>" . htmlentities($row['farmName']) . "</td>";
        echo "<td>" . htmlentities($row['contactNumber']) . "</td>";
        echo "<td>" . htmlentities($row['emailAddress']) . "</td>";
        echo "<td>";
        echo('<a href="edit.php?table=farmers&id=' . $row['id'] . '">Edit</a> / ');
        echo('<form method="POST" action="delete.php" style="display:inline;">');
        echo '<input type="hidden" name="farmer_id" value="' . $row['id'] . '">';
        echo '<input type="submit" value="Delete" onclick="return confirm(\'Delete this farmer?\')">';
        echo '</form>';
        echo("</td>");
        echo("</tr>\n");
    }
    echo "</table>";
    echo '<p><a href="add.php?table=farmers">Add New</a></p>';

    // Livestock Information
    echo "<h2>Livestock Information</h2>";
    echo "<table border='1'><tr><th>Animal ID</th><th>Species</th><th>Date of Birth</th><th>Breed</th><th>Actions</th></tr>";
    $stmt = $pdo->query("SELECT animal_id AS animalID, species, date_of_birth, breed, animal_id FROM livestock");
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlentities($row['animalID']) . "</td>";
        echo "<td>" . htmlentities($row['species']) . "</td>";
        echo "<td>" . htmlentities($row['date_of_birth']) . "</td>";
        echo "<td>" . htmlentities($row['breed']) . "</td>";
        echo "<td>";
        echo('<a href="edit.php?table=livestock&animal_id=' . urlencode($row['animal_id']) . '">Edit</a> / ');
        echo('<form method="POST" action="delete.php" style="display:inline;">');
        echo '<input type="hidden" name="animal_id" value="' . $row['animal_id'] . '">';
        echo '<input type="submit" value="Delete" onclick="return confirm(\'Delete this livestock record?\')">';
        echo '</form>';
        echo("</td>");
        echo("</tr>\n");
    }
    echo "</table>";
    echo '<p><a href="add.php?table=livestock">Add New</a></p>';

    // Health Records
    echo "<h2>Health Records</h2>";
    echo "<table border='1'><tr><th>Animal ID</th><th>Checkup Date</th><th>Notes</th><th>Actions</th></tr>";
    $stmt = $pdo->query("SELECT animal_id, checkup_date, notes FROM health_records");
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
        echo "<td>" . htmlentities($row['checkup_date']) . "</td>";
        echo "<td>" . htmlentities($row['notes']) . "</td>";
        echo "<td>";
        echo('<a href="edit.php?table=health_records&animal_id=' . urlencode($row['animal_id']) . '&checkup_date=' . urlencode($row['checkup_date']) . '">Edit</a> / ');
        echo('<form method="POST" action="delete.php" style="display:inline;">');
        echo '<input type="hidden" name="health_animal_id" value="' . $row['animal_id'] . '">';
        echo '<input type="hidden" name="health_date" value="' . $row['checkup_date'] . '">';
        echo '<input type="submit" value="Delete" onclick="return confirm(\'Delete this health record?\')">';
        echo '</form>';
        echo("</td>");
        echo("</tr>\n");
    }
    echo "</table>";
    echo '<p><a href="add.php?table=health_records">Add New</a></p>';

    // Breeding Records
    echo "<h2>Breeding Information</h2>";
    echo "<table border='1'><tr><th>Animal ID</th><th>Breeding Date</th><th>Sire ID</th><th>Notes</th><th>Actions</th></tr>";
    $stmt = $pdo->query("SELECT animal_id, breeding_date, sire_id, notes FROM breeding_records");
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
        echo "<td>" . htmlentities($row['breeding_date']) . "</td>";
        echo "<td>" . htmlentities($row['sire_id']) . "</td>";
        echo "<td>" . htmlentities($row['notes']) . "</td>";
        echo "<td>";
        echo('<a href="edit.php?table=breeding_records&animal_id=' . urlencode($row['animal_id']) . '&breeding_date=' . urlencode($row['breeding_date']) . '">Edit</a> / ');
        echo('<form method="POST" action="delete.php" style="display:inline;">');
        echo '<input type="hidden" name="breeding_animal_id" value="' . $row['animal_id'] . '">';
        echo '<input type="hidden" name="breeding_date" value="' . $row['breeding_date'] . '">';
        echo '<input type="submit" value="Delete" onclick="return confirm(\'Delete this breeding record?\')">';
        echo '</form>';
        echo("</td>");
        echo("</tr>\n");
    }
    echo "</table>";
    echo '<p><a href="add.php?table=breeding_records">Add New</a></p>';

    // Feeding Records
    echo "<h2>Feeding & Nutrients</h2>";
    echo "<table border='1'><tr><th>Animal ID</th><th>Record Date</th><th>Feed Type</th><th>Notes</th><th>Actions</th></tr>";
    $stmt = $pdo->query("SELECT animal_id, record_date, feed_type, notes FROM feeding_records");
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
        echo "<td>" . htmlentities($row['record_date']) . "</td>";
        echo "<td>" . htmlentities($row['feed_type']) . "</td>";
        echo "<td>" . htmlentities($row['notes']) . "</td>";
        echo "<td>";
        echo('<a href="edit.php?table=feeding_records&animal_id=' . urlencode($row['animal_id']) . '&record_date=' . urlencode($row['record_date']) . '&feed_type=' . urlencode($row['feed_type']) . '">Edit</a> / ');
        echo('<form method="POST" action="delete.php" style="display:inline;">');
        echo '<input type="hidden" name="feeding_animal_id" value="' . $row['animal_id'] . '">';
        echo '<input type="hidden" name="feeding_date" value="' . $row['record_date'] . '">';
        echo '<input type="hidden" name="feeding_type" value="' . $row['feed_type'] . '">';
        echo '<input type="submit" value="Delete" onclick="return confirm(\'Delete this feeding record?\')">';
        echo '</form>';
        echo("</td>");
        echo("</tr>\n");
    }
    echo "</table>";
    echo '<p><a href="add.php?table=feeding_records">Add New</a></p>';

    // Vaccination Records
    echo "<h2>Vaccination Records</h2>";
    echo "<table border='1'><tr><th>Animal ID</th><th>Vaccine Name</th><th>Administration Date</th><th>Next Due Date</th><th>Administered By</th><th>Notes</th><th>Actions</th></tr>";
    $stmt = $pdo->query("SELECT animal_id, vaccine_name, administration_date, next_due_date, administered_by, notes FROM vaccination_records");
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
        echo "<td>" . htmlentities($row['vaccine_name']) . "</td>";
        echo "<td>" . htmlentities($row['administration_date']) . "</td>";
        echo "<td>" . htmlentities($row['next_due_date']) . "</td>";
        echo "<td>" . htmlentities($row['administered_by']) . "</td>";
        echo "<td>" . htmlentities($row['notes']) . "</td>";
        echo "<td>";
        echo('<a href="edit.php?table=vaccination_records&animal_id=' . urlencode($row['animal_id']) . '&vaccine_name=' . urlencode($row['vaccine_name']) . '&administration_date=' . urlencode($row['administration_date']) . '">Edit</a> / ');
        echo('<form method="POST" action="delete.php" style="display:inline;">');
        echo '<input type="hidden" name="vaccination_animal_id" value="' . $row['animal_id'] . '">';
        echo '<input type="hidden" name="vaccination_vaccine_name" value="' . $row['vaccine_name'] . '">';
        echo '<input type="hidden" name="vaccination_date" value="' . $row['administration_date'] . '">';
        echo '<input type="submit" value="Delete" onclick="return confirm(\'Delete this vaccination record?\')">';
        echo '</form>';
        echo("</td>");
        echo("</tr>\n");
    }
    echo "</table>";
    echo '<p><a href="add.php?table=vaccination_records">Add New</a></p>';

    // Mortality Records
    echo "<h2>Mortality Rates</h2>";
    echo "<table border='1'><tr><th>Animal ID</th><th>Date of Death</th><th>Cause</th><th>Actions</th></tr>";
    $stmt = $pdo->query("SELECT animal_id, date_of_death, cause_of_death FROM mortality_records");
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
        echo "<td>" . htmlentities($row['date_of_death']) . "</td>";
        echo "<td>" . htmlentities($row['cause_of_death']) . "</td>";
        echo "<td>";
        echo('<a href="edit.php?table=mortality_records&animal_id=' . urlencode($row['animal_id']) . '&date_of_death=' . urlencode($row['date_of_death']) . '">Edit</a> / ');
        echo('<form method="POST" action="delete.php" style="display:inline;">');
        echo '<input type="hidden" name="mortality_animal_id" value="' . $row['animal_id'] . '">';
        echo '<input type="hidden" name="mortality_date" value="' . $row['date_of_death'] . '">';
        echo '<input type="submit" value="Delete" onclick="return confirm(\'Delete this mortality record?\')">';
        echo '</form>';
        echo("</td>");
        echo("</tr>\n");
    }
    echo "</table>";
    echo '<p><a href="add.php?table=mortality_records">Add New</a></p>';

    // Movement & Sales Records
    echo "<h2>Movement & Sales Records</h2>";
    echo "<table border='1'><tr><th>Animal ID</th><th>Movement Type</th><th>Date</th><th>Notes</th><th>Actions</th></tr>";
    $stmt = $pdo->query("SELECT animal_id, movement_type, movement_date, notes, id FROM movement_records");
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
        echo "<td>" . htmlentities($row['movement_type']) . "</td>";
        echo "<td>" . htmlentities($row['movement_date']) . "</td>";
        echo "<td>" . htmlentities($row['notes']) . "</td>";
        echo "<td>";
        echo('<a href="edit.php?table=movement_records&id=' . $row['id'] . '">Edit</a> / ');
        echo('<form method="POST" action="delete.php" style="display:inline;">');
        echo '<input type="hidden" name="movement_id" value="' . $row['id'] . '">';
        echo '<input type="submit" value="Delete" onclick="return confirm(\'Delete this movement record?\')">';
        echo '</form>';
        echo("</td>");
        echo("</tr>\n");
    }
    echo "</table>";
    echo '<p><a href="add.php?table=movement_records">Add New</a></p>';

} catch (PDOException $e) {
    error_log("Query failed: " . $e->getMessage());
    echo '<p style="color: red;">Error loading data: ' . htmlentities($e->getMessage()) . "</p>\n";
}
?>

</body>
</html>