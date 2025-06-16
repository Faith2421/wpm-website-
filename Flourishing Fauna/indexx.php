<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Farmer Details</title>
</head>
<body>
    <h2>Farmer Information</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }
    ?>
    
    <table border="1">
        <tr>
            <th>Farmer Name</th>
            <th>Farm Name</th>
            <th>Contact Number</th>
            <th>Email Address</th>
            <th>Actions</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT farmerName, farmName, contactNumber, emailAddress, farmer_id FROM farmer_detail");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".htmlentities($row['farmerName'])."</td>";
            echo "<td>".htmlentities($row['farmName'])."</td>";
            echo "<td>".htmlentities($row['contactNumber'])."</td>";
            echo "<td>".htmlentities($row['emailAddress'])."</td>";
            echo "<td>";
            echo '<a href="edit.php?farmer_id='.$row['farmer_id'].'">Edit</a> / ';
            echo '<a href="delete.php?farmer_id='.$row['farmer_id'].'">Delete</a>';
            echo "</td>";
            echo "</tr>\n";
        }
        ?>
    </table>
    <br>
    <a href="add.php">Add New Farmer</a>
</body>
</html>

<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Livestock Information</title>
</head>
<body>
    <h2>Livestock Information</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }
    ?>
    
    <table border="1">
        <tr>
            <th>Animal ID</th>
            <th>Species</th>
            <th>Date of Birth</th>
            <th>Breed</th>
            <th>Actions</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT animalID, species, dateOfBirth, breed, animal_id FROM livestock_information");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".htmlentities($row['animalID'])."</td>";
            echo "<td>".htmlentities($row['species'])."</td>";
            echo "<td>".htmlentities($row['dateOfBirth'])."</td>";
            echo "<td>".htmlentities($row['breed'])."</td>";
            echo "<td>";
            echo '<a href="edit.php?animal_id='.$row['animal_id'].'">Edit</a> / ';
            echo '<a href="delete.php?animal_id='.$row['animal_id'].'">Delete</a>';
            echo "</td>";
            echo "</tr>\n";
        }
        ?>
    </table>
    <br>
    <a href="add.php">Add New Animal</a>
</body>
</html>

<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Health Records</title>
</head>
<body>
    <h2>Health Records</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }
    ?>
    
    <table border="1">
        <tr>
            <th>Animal ID</th>
            <th>Checkup Date</th>
            <th>Health Notes</th>
            <th>Actions</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT animalID, checkupDate, healthNotes, animal_id FROM health_records");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".htmlentities($row['animalID'])."</td>";
            echo "<td>".htmlentities($row['checkupDate'])."</td>";
            echo "<td>".htmlentities($row['healthNotes'])."</td>";
            echo "<td>";
            echo '<a href="edit.php?animal_id='.$row['animal_id'].'">Edit</a> / ';
            echo '<a href="delete.php?animal_id='.$row['animal_id'].'">Delete</a>';
            echo "</td>";
            echo "</tr>\n";
        }
        ?>
    </table>
    <br>
    <a href="add.php">Add New Health Record</a>
</body>
</html>

<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Breeding Information</title>
</head>
<body>
    <h2>Breeding Information</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }
    ?>
    
    <table border="1">
        <tr>
            <th>Animal ID</th>
            <th>Breeding Date</th>
            <th>Sire ID</th>
            <th>Breeding Notes</th>
            <th>Actions</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT animalID, breedingDate, sireID, breedingNotes, animal_id FROM breeding_information");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".htmlentities($row['animalID'])."</td>";
            echo "<td>".htmlentities($row['breedingDate'])."</td>";
            echo "<td>".htmlentities($row['sireID'])."</td>";
            echo "<td>".htmlentities($row['breedingNotes'])."</td>";
            echo "<td>";
            echo '<a href="edit.php?animal_id='.$row['animal_id'].'">Edit</a> / ';
            echo '<a href="delete.php?animal_id='.$row['animal_id'].'">Delete</a>';
            echo "</td>";
            echo "</tr>\n";
        }
        ?>
    </table>
    <br>
    <a href="add.php">Add New Breeding Record</a>
</body>
</html>

<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Feeding & Nutrition</title>
</head>
<body>
    <h2>Feeding & Nutrition</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }
    ?>
    
    <table border="1">
        <tr>
            <th>Animal ID</th>
            <th>Feeding Schedule Notes</th>
            <th>Actions</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT animalID, feedingScheduleNotes, animal_id FROM feeding_nutrients");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".htmlentities($row['animalID'])."</td>";
            echo "<td>".htmlentities($row['feedingScheduleNotes'])."</td>";
            echo "<td>";
            echo '<a href="edit.php?animal_id='.$row['animal_id'].'">Edit</a> / ';
            echo '<a href="delete.php?animal_id='.$row['animal_id'].'">Delete</a>';
            echo "</td>";
            echo "</tr>\n";
        }
        ?>
    </table>
    <br>
    <a href="add.php">Add New Feeding Record</a>
</body>
</html>

<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mortality Records</title>
</head>
<body>
    <h2>Mortality Records</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }
    ?>
    
    <table border="1">
        <tr>
            <th>Animal ID</th>
            <th>Date of Death</th>
            <th>Cause</th>
            <th>Actions</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT animalID, dateOfDeath, cause, animal_id FROM mortality_rates");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".htmlentities($row['animalID'])."</td>";
            echo "<td>".htmlentities($row['dateOfDeath'])."</td>";
            echo "<td>".htmlentities($row['cause'])."</td>";
            echo "<td>";
            echo '<a href="edit.php?animal_id='.$row['animal_id'].'">Edit</a> / ';
            echo '<a href="delete.php?animal_id='.$row['animal_id'].'">Delete</a>';
            echo "</td>";
            echo "</tr>\n";
        }
        ?>
    </table>
    <br>
    <a href="add.php">Add New Mortality Record</a>
</body>
</html>

<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Movement & Sales Records</title>
</head>
<body>
    <h2>Movement & Sales Records</h2>
    <?php
    if (isset($_SESSION['error'])) {
        echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
        unset($_SESSION['success']);
    }
    ?>
    
    <table border="1">
        <tr>
            <th>Animal ID</th>
            <th>Movement</th>
            <th>Date</th>
            <th>Details</th>
            <th>Actions</th>
        </tr>
        <?php
        $stmt = $pdo->query("SELECT animalID, movement, date, details, animal_id FROM movement_salesrecords");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".htmlentities($row['animalID'])."</td>";
            echo "<td>".htmlentities($row['movement'])."</td>";
            echo "<td>".htmlentities($row['date'])."</td>";
            echo "<td>".htmlentities($row['details'])."</td>";
            echo "<td>";
            echo '<a href="edit.php?animal_id='.$row['animal_id'].'">Edit</a> / ';
            echo '<a href="delete.php?animal_id='.$row['animal_id'].'">Delete</a>';
            echo "</td>";
            echo "</tr>\n";
        }
        ?>
    </table>
    <br>
    <a href="add.php">Add New Movement/Sales Record</a>
</body>
</html>