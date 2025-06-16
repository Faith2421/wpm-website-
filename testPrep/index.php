<?php
require_once "pdo.php";
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmTrack - Livestock Management Dashboard</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f9f0;
            color: #333;
        }
        header {
            background: #228B22;
            color: #fff;
            padding: 1rem;
            text-align: center;
        }
        .container {
            padding: 2rem;
        }
        .section {
            margin-bottom: 3rem;
            background: white;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section h2 {
            color: #228B22;
            border-bottom: 2px solid #228B22;
            padding-bottom: 0.5rem;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #228B22;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .action-links a {
            color: #228B22;
            text-decoration: none;
            margin-right: 10px;
            font-weight: bold;
        }
        .action-links a:hover {
            text-decoration: underline;
        }
        .add-btn {
            background: #228B22;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-top: 1rem;
        }
        .add-btn:hover {
            background: #1a6b1a;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            border-radius: 4px;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .navigation {
            background: #145214;
            padding: 1rem;
            text-align: center;
        }
        .navigation a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
            font-weight: bold;
        }
        .navigation a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <header>
        <h1>Flourishing Fauna - Management Dashboard</h1>
    </header>
    
    <div class="navigation">
        <a href="Home.php">Add New Records</a>
        <a href="#farmers">Farmers</a>
        <a href="#livestock">Livestock</a>
        <a href="#health">Health Records</a>
        <a href="#breeding">Breeding</a>
        <a href="#feeding">Feeding</a>
        <a href="#mortality">Mortality</a>
        <a href="#movement">Movement</a>
    </div>

    <div class="container">
        <?php
        // Display error and success messages
        if (isset($_SESSION['error'])) {
            echo '<div class="message error">' . htmlentities($_SESSION['error']) . "</div>\n";
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="message success">' . htmlentities($_SESSION['success']) . "</div>\n";
            unset($_SESSION['success']);
        }
        ?>

        <!-- Farmers Section -->
        <div id="farmers" class="section">
            <h2>Farmer Details</h2>
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM farmers ORDER BY farmer_id DESC");
                if ($stmt->rowCount() > 0) {
                    echo '<table>';
                    echo '<tr><th>ID</th><th>Farmer Name</th><th>Farm Name</th><th>Contact</th><th>Email</th><th>Actions</th></tr>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlentities($row['farmer_id']) . "</td>";
                        echo "<td>" . htmlentities($row['farmer_name']) . "</td>";
                        echo "<td>" . htmlentities($row['farm_name']) . "</td>";
                        echo "<td>" . htmlentities($row['contact_number']) . "</td>";
                        echo "<td>" . htmlentities($row['email_address']) . "</td>";
                        echo '<td class="action-links">';
                        echo '<a href="edit_farmer.php?farmer_id=' . $row['farmer_id'] . '">Edit</a>';
                        echo '<a href="delete_farmer.php?farmer_id=' . $row['farmer_id'] . '">Delete</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p>No farmer records found.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="message error">Error loading farmer data: ' . htmlentities($e->getMessage()) . '</p>';
            }
            ?>
            <a href="Home.php#farmer" class="add-btn">Add New Farmer</a>
        </div>

        <!-- Livestock Section -->
        <div id="livestock" class="section">
            <h2>Livestock Information</h2>
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM livestock ORDER BY animal_id DESC");
                if ($stmt->rowCount() > 0) {
                    echo '<table>';
                    echo '<tr><th>Animal ID</th><th>Species</th><th>Breed</th><th>Date of Birth</th><th>Age</th><th>Actions</th></tr>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $age = '';
                        if ($row['date_of_birth']) {
                            $birth_date = new DateTime($row['date_of_birth']);
                            $today = new DateTime();
                            $age = $birth_date->diff($today)->format('%y years, %m months');
                        }
                        echo "<tr>";
                        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
                        echo "<td>" . htmlentities($row['species']) . "</td>";
                        echo "<td>" . htmlentities($row['breed']) . "</td>";
                        echo "<td>" . htmlentities($row['date_of_birth']) . "</td>";
                        echo "<td>" . htmlentities($age) . "</td>";
                        echo '<td class="action-links">';
                        echo '<a href="edit_livestock.php?animal_id=' . urlencode($row['animal_id']) . '">Edit</a>';
                        echo '<a href="delete_livestock.php?animal_id=' . urlencode($row['animal_id']) . '">Delete</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p>No livestock records found.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="message error">Error loading livestock data: ' . htmlentities($e->getMessage()) . '</p>';
            }
            ?>
            <a href="Home.php#livestock" class="add-btn">Add New Livestock</a>
        </div>

        <!-- Health Records Section -->
        <div id="health" class="section">
            <h2>Health Records</h2>
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM health_records ORDER BY checkup_date DESC LIMIT 20");
                if ($stmt->rowCount() > 0) {
                    echo '<table>';
                    echo '<tr><th>Record ID</th><th>Animal ID</th><th>Checkup Date</th><th>Health Notes</th><th>Actions</th></tr>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlentities($row['record_id']) . "</td>";
                        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
                        echo "<td>" . htmlentities($row['checkup_date']) . "</td>";
                        echo "<td>" . htmlentities(substr($row['health_notes'], 0, 50)) . "...</td>";
                        echo '<td class="action-links">';
                        echo '<a href="edit_health.php?record_id=' . $row['record_id'] . '">Edit</a>';
                        echo '<a href="delete_health.php?record_id=' . $row['record_id'] . '">Delete</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p>No health records found.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="message error">Error loading health data: ' . htmlentities($e->getMessage()) . '</p>';
            }
            ?>
            <a href="Home.php#health" class="add-btn">Add New Health Record</a>
        </div>

        <!-- Breeding Records Section -->
        <div id="breeding" class="section">
            <h2>Breeding Information</h2>
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM breeding_records ORDER BY breeding_date DESC LIMIT 20");
                if ($stmt->rowCount() > 0) {
                    echo '<table>';
                    echo '<tr><th>Record ID</th><th>Animal ID</th><th>Breeding Date</th><th>Sire ID</th><th>Notes</th><th>Actions</th></tr>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlentities($row['record_id']) . "</td>";
                        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
                        echo "<td>" . htmlentities($row['breeding_date']) . "</td>";
                        echo "<td>" . htmlentities($row['sire_id']) . "</td>";
                        echo "<td>" . htmlentities(substr($row['breeding_notes'], 0, 30)) . "...</td>";
                        echo '<td class="action-links">';
                        echo '<a href="edit_breeding.php?record_id=' . $row['record_id'] . '">Edit</a>';
                        echo '<a href="delete_breeding.php?record_id=' . $row['record_id'] . '">Delete</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p>No breeding records found.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="message error">Error loading breeding data: ' . htmlentities($e->getMessage()) . '</p>';
            }
            ?>
            <a href="Home.php#breeding" class="add-btn">Add New Breeding Record</a>
        </div>

        <!-- Feeding Records Section -->
        <div id="feeding" class="section">
            <h2>Feeding & Nutrients</h2>
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM feeding_records ORDER BY record_id DESC LIMIT 20");
                if ($stmt->rowCount() > 0) {
                    echo '<table>';
                    echo '<tr><th>Record ID</th><th>Animal ID</th><th>Feeding Schedule/Notes</th><th>Actions</th></tr>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlentities($row['record_id']) . "</td>";
                        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
                        echo "<td>" . htmlentities(substr($row['feeding_schedule_notes'], 0, 100)) . "...</td>";
                        echo '<td class="action-links">';
                        echo '<a href="edit_feeding.php?record_id=' . $row['record_id'] . '">Edit</a>';
                        echo '<a href="delete_feeding.php?record_id=' . $row['record_id'] . '">Delete</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p>No feeding records found.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="message error">Error loading feeding data: ' . htmlentities($e->getMessage()) . '</p>';
            }
            ?>
            <a href="Home.php#feeding" class="add-btn">Add New Feeding Record</a>
        </div>

        <!-- Mortality Records Section -->
        <div id="mortality" class="section">
            <h2>Mortality Records</h2>
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM mortality_records ORDER BY date_of_death DESC LIMIT 20");
                if ($stmt->rowCount() > 0) {
                    echo '<table>';
                    echo '<tr><th>Record ID</th><th>Animal ID</th><th>Date of Death</th><th>Cause</th><th>Actions</th></tr>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlentities($row['record_id']) . "</td>";
                        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
                        echo "<td>" . htmlentities($row['date_of_death']) . "</td>";
                        echo "<td>" . htmlentities(substr($row['cause'], 0, 50)) . "...</td>";
                        echo '<td class="action-links">';
                        echo '<a href="edit_mortality.php?record_id=' . $row['record_id'] . '">Edit</a>';
                        echo '<a href="delete_mortality.php?record_id=' . $row['record_id'] . '">Delete</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p>No mortality records found.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="message error">Error loading mortality data: ' . htmlentities($e->getMessage()) . '</p>';
            }
            ?>
            <a href="Home.php#mortality" class="add-btn">Add New Mortality Record</a>
        </div>

        <!-- Movement Records Section -->
        <div id="movement" class="section">
            <h2>Movement & Sales Records</h2>
            <?php
            try {
                $stmt = $pdo->query("SELECT * FROM movement_records ORDER BY date DESC LIMIT 20");
                if ($stmt->rowCount() > 0) {
                    echo '<table>';
                    echo '<tr><th>Record ID</th><th>Animal ID</th><th>Movement Type</th><th>Date</th><th>Details</th><th>Actions</th></tr>';
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        echo "<tr>";
                        echo "<td>" . htmlentities($row['record_id']) . "</td>";
                        echo "<td>" . htmlentities($row['animal_id']) . "</td>";
                        echo "<td>" . htmlentities($row['movement']) . "</td>";
                        echo "<td>" . htmlentities($row['date']) . "</td>";
                        echo "<td>" . htmlentities(substr($row['details'], 0, 30)) . "...</td>";
                        echo '<td class="action-links">';
                        echo '<a href="edit_movement.php?record_id=' . $row['record_id'] . '">Edit</a>';
                        echo '<a href="delete_movement.php?record_id=' . $row['record_id'] . '">Delete</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                    echo '</table>';
                } else {
                    echo '<p>No movement records found.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="message error">Error loading movement data: ' . htmlentities($e->getMessage()) . '</p>';
            }
            ?>
            <a href="Home.php#movement" class="add-btn">Add New Movement Record</a>
        </div>
    </div>

    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>