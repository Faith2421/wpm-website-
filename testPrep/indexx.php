<?php
session_start(); // Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "livestock_management";
// Start the session
// Database connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=your_database_name', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    exit;
}
if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

echo('<table border="1">'."\n");
// Fixed: Removed "farmer Details" header and made headers match data
echo('<tr><th>Farmer Name</th><th>Farm Name</th><th>Contact Number</th><th>Email Address</th><th>Actions</th></tr>'."\n");

$stmt = $pdo->query("SELECT farmerName, farmName, contactNumber, emailAddress, farmer_id FROM farmers");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr>";
    echo "<td>" . htmlentities($row['farmerName']) . "</td>";
    echo "<td>" . htmlentities($row['farmName']) . "</td>";
    echo "<td>" . htmlentities($row['contactNumber']) . "</td>";
    echo "<td>" . htmlentities($row['emailAddress']) . "</td>";
    echo "<td>";
    // Fixed: Use consistent parameter name (farmer_id) and point to form pages
    echo('<a href="edit_farmer.php?farmer_id='.$row['farmer_id'].'">Edit</a> / ');
    echo('<a href="delete.php?farmer_id='.$row['farmer_id'].'" onclick="return confirm(\'Delete this farmer?\')">Delete</a>');
    echo("</td>");
    echo("</tr>\n");
}
echo("</table>\n");
?>

<!-- Fixed: Point to form page, not processing script -->
<p><a href="add.php">Add New</a></p> 






<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

echo('<table border="1">'."\n");
// Fixed: Removed "farmer Details" header and made headers match data
echo('<tr><th>Livestock Informatioin</th><th> animalID</th><th>species</th><th>dateOfBirth</th><th>breed</th></tr>'."\n");

$stmt = $pdo->query("SELECT animalID,species,dateOfBirth, breed, animal_id FROM livestock");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr>";
    echo "<td>" . htmlentities($row['animalID']) . "</td>";
    echo "<td>" . htmlentities($row['species']) . "</td>";
    echo "<td>" . htmlentities($row['dateOfBirth']) . "</td>";
    echo "<td>" . htmlentities($row['breed']) . "</td>";
    echo "<td>";
    // Fixed: Use consistent parameter name (farmer_id) and point to form pages
    echo('<a href="edit_farmer.php?farmer_id='.$row['animal_id'].'">Edit</a> / ');
    echo('<a href="delete.php?farmer_id='.$row['animal_id'].'" onclick="return confirm(\'Delete this livestock record?\')">Delete</a>');
    echo("</td>");
    echo("</tr>\n");
}
echo("</table>\n");
?>

<!-- Fixed: Point to form page, not processing script -->
<p><a href="add.php">Add New</a></p>




<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

echo('<table border="1">'."\n");
// Fixed: Removed "farmer Details" header and made headers match data
echo('<tr><th>Health Records</th><th> animalID</th><th>checkupDate</th><th>healthNotes</th><th>breeding Notes</th></tr>'."\n");

$stmt = $pdo->query("SELECT animalID,checkupDate,healthNotes,  animal_id FROM health_records");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr>";
    echo "<td>" . htmlentities($row['animalID']) . "</td>";
    echo "<td>" . htmlentities($row['checkupDate']) . "</td>";
    echo "<td>" . htmlentities($row['healthNotes']) . "</td>";
    echo "<td>";
    // Fixed: Use consistent parameter name (farmer_id) and point to form pages
    echo('<a href="edit_farmer.php?farmer_id='.$row['animal_id'].'">Edit</a> / ');
    echo('<a href="delete.php?farmer_id='.$row['animal_id'].'" onclick="return confirm(\'Delete this health record?\')">Delete</a>');
    echo("</td>");
    echo("</tr>\n");
}
echo("</table>\n");
?>






<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

echo('<table border="1">'."\n");
// Fixed: Removed "farmer Details" header and made headers match data
echo('<tr><th>breeding Information</th><th> animalID</th><th>breeding Date</th><th>sire ID</th><th>breeding Notes</th></tr>'."\n");

$stmt = $pdo->query("SELECT animalID,breedingDate,sireID, breedingNotes, animal_id FROM breeding_information");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr>";
    echo "<td>" . htmlentities($row['animalID']) . "</td>";
    echo "<td>" . htmlentities($row['species']) . "</td>";
    echo "<td>" . htmlentities($row['dateOfBirth']) . "</td>";
    echo "<td>" . htmlentities($row['breed']) . "</td>";
    echo "<td>";
    // Fixed: Use consistent parameter name (farmer_id) and point to form pages
    echo('<a href="edit_farmer.php?farmer_id='.$row['animal_id'].'">Edit</a> / ');
    echo('<a href="delete.php?farmer_id='.$row['animal_id'].'" onclick="return confirm(\'Delete this breeding information record?\')">Delete</a>');
    echo("</td>");
    echo("</tr>\n");
}
echo("</table>\n");
?>

<!-- Fixed: Point to form page, not processing script -->
<p><a href="add.php">Add New</a></p>









<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

echo('<table border="1">'."\n");
// Fixed: Removed "farmer Details" header and made headers match data
echo('<tr><th>Feeding & Nutrient</th><th> animal ID</th><th>checkupDate</th><th>healthNotes</th><th>breeding Notes</th></tr>'."\n");

$stmt = $pdo->query("SELECT animalID,feedingScheduleNotes, animal_id FROM feeding_nutrients");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr>";
    echo "<td>" . htmlentities($row['animalID']) . "</td>";
    echo "<td>" . htmlentities($row['feedingScheduleNotes']) . "</td>";
    echo "<td>";
    // Fixed: Use consistent parameter name (farmer_id) and point to form pages
    echo('<a href="edit_farmer.php?farmer_id='.$row['animal_id'].'">Edit</a> / ');
    echo('<a href="delete.php?farmer_id='.$row['animal_id'].'" onclick="return confirm(\'Delete this feeding nutrients record?\')">Delete</a>');
    echo("</td>");
    echo("</tr>\n");
}
echo("</table>\n");
?>





<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

echo('<table border="1">'."\n");
// Fixed: Removed "farmer Details" header and made headers match data
echo('<tr><th>Vaccination Records</th><th> vaccineName </th><th>administrationDate</th><th>nextDueDate</th><th>administeredBy</th><th>vaccinationNotes</th></tr>'."\n");

$stmt = $pdo->query("SELECT vaccineName,administrationDate,nextDueDate,administeredBy,vaccinationNotes, animal_id FROM vaccination");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr>";
    echo "<td>" . htmlentities($row['animalID']) . "</td>";
    echo "<td>" . htmlentities($row['administrationDate']) . "</td>";
    echo "<td>" . htmlentities($row['nextDueDate']) . "</td>";
    echo "<td>" . htmlentities($row['administeredBy']) . "</td>";
    echo "<td>" . htmlentities($row['vaccinationNotes']) . "</td>";
    echo "<td>";
    // Fixed: Use consistent parameter name (farmer_id) and point to form pages
    echo('<a href="edit_farmer.php?farmer_id='.$row['animal_id'].'">Edit</a> / ');
    echo('<a href="delete.php?farmer_id='.$row['animal_id'].'" onclick="return confirm(\'Delete this vaccination record?\')">Delete</a>');
    echo("</td>");
    echo("</tr>\n");
}
echo("</table>\n");
?>



<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

echo('<table border="1">'."\n");
// Fixed: Removed "farmer Details" header and made headers match data
echo('<tr><th>Mortality Rates</th><th> animalID</th><th>dateOfDeath</th><th>cause</th></tr>'."\n");

$stmt = $pdo->query("SELECT animalID,dateOfDeath ,cause, animal_id FROM mortality_rates");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr>";
    echo "<td>" . htmlentities($row['animalID']) . "</td>";
    echo "<td>" . htmlentities($row['dateOfDeath']) . "</td>";
    echo "<td>" . htmlentities($row['cause']) . "</td>";
    echo "<td>";
    // Fixed: Use consistent parameter name (farmer_id) and point to form pages
    echo('<a href="edit_farmer.php?farmer_id='.$row['animal_id'].'">Edit</a> / ');
    echo('<a href="delete.php?farmer_id='.$row['animal_id'].'" onclick="return confirm(\'Delete this mortality rate record?\')">Delete</a>');
    echo("</td>");
    echo("</tr>\n");
}
echo("</table>\n");
?>


<?php
if ( isset($_SESSION['error']) ) {
    echo '<p style="color: red;">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}
if ( isset($_SESSION['success']) ) {
    echo '<p style="color: green;">'.$_SESSION['success']."</p>\n";
    unset($_SESSION['success']);
}

echo('<table border="1">'."\n");
// Fixed: Removed "farmer Details" header and made headers match data
echo('<tr><th>movement & Sales Records</th><th>animalID </th><th>movement</th><th>date</th><th>details</th></tr>'."\n");

$stmt = $pdo->query("SELECT animalID,movement,date,details, animal_id FROM vaccination");
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    echo "<tr>";
    echo "<td>" . htmlentities($row['animalID']) . "</td>";
    echo "<td>" . htmlentities($row['movement']) . "</td>";
    echo "<td>" . htmlentities($row['date']) . "</td>";
    echo "<td>" . htmlentities($row['details']) . "</td>";
    echo "<td>";
    // Fixed: Use consistent parameter name (farmer_id) and point to form pages
    echo('<a href="edit_farmer.php?farmer_id='.$row['animal_id'].'">Edit</a> / ');
    echo('<a href="delete.php?farmer_id='.$row['animal_id'].'" onclick="return confirm(\'Delete this vaccination record?\')">Delete</a>');
    echo("</td>");
    echo("</tr>\n");
}
echo("</table>\n");
?> 