<?php
require_once "pdo.php";

// Check if PDO connection is successful
if ($pdo === null) {
    echo '<p style="color: red;">Database connection failed. Please check the configuration or ensure MySQL is running on port 3307.</p>';
    exit;
}

$table = $_GET['table'] ?? '';
$allowed_tables = ['farmers', 'livestock', 'health_records', 'breeding_records', 'feeding_records', 'vaccination_records', 'mortality_records', 'movement_records'];
if (!in_array($table, $allowed_tables)) {
    echo '<p style="color: red;">Invalid table specified.</p>';
    exit;
}

if ($table === 'farmers' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM farmers WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $row = $stmt->fetch();
    $id_field = 'id';
    $id_value = $_GET['id'];
} elseif ($table === 'livestock' && isset($_GET['animal_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM livestock WHERE animal_id = ?");
    $stmt->execute([$_GET['animal_id']]);
    $row = $stmt->fetch();
    $id_field = 'animal_id';
    $id_value = $_GET['animal_id'];
} elseif ($table === 'health_records' && isset($_GET['animal_id']) && isset($_GET['checkup_date'])) {
    $stmt = $pdo->prepare("SELECT * FROM health_records WHERE animal_id = ? AND checkup_date = ?");
    $stmt->execute([$_GET['animal_id'], $_GET['checkup_date']]);
    $row = $stmt->fetch();
    $id_field = ['animal_id', 'checkup_date'];
    $id_value = [$_GET['animal_id'], $_GET['checkup_date']];
} elseif ($table === 'breeding_records' && isset($_GET['animal_id']) && isset($_GET['breeding_date'])) {
    $stmt = $pdo->prepare("SELECT * FROM breeding_records WHERE animal_id = ? AND breeding_date = ?");
    $stmt->execute([$_GET['animal_id'], $_GET['breeding_date']]);
    $row = $stmt->fetch();
    $id_field = ['animal_id', 'breeding_date'];
    $id_value = [$_GET['animal_id'], $_GET['breeding_date']];
} elseif ($table === 'feeding_records' && isset($_GET['animal_id']) && isset($_GET['record_date']) && isset($_GET['feed_type'])) {
    $stmt = $pdo->prepare("SELECT * FROM feeding_records WHERE animal_id = ? AND record_date = ? AND feed_type = ?");
    $stmt->execute([$_GET['animal_id'], $_GET['record_date'], $_GET['feed_type']]);
    $row = $stmt->fetch();
    $id_field = ['animal_id', 'record_date', 'feed_type'];
    $id_value = [$_GET['animal_id'], $_GET['record_date'], $_GET['feed_type']];
} elseif ($table === 'vaccination_records' && isset($_GET['animal_id']) && isset($_GET['vaccine_name']) && isset($_GET['administration_date'])) {
    $stmt = $pdo->prepare("SELECT * FROM vaccination_records WHERE animal_id = ? AND vaccine_name = ? AND administration_date = ?");
    $stmt->execute([$_GET['animal_id'], $_GET['vaccine_name'], $_GET['administration_date']]);
    $row = $stmt->fetch();
    $id_field = ['animal_id', 'vaccine_name', 'administration_date'];
    $id_value = [$_GET['animal_id'], $_GET['vaccine_name'], $_GET['administration_date']];
} elseif ($table === 'mortality_records' && isset($_GET['animal_id']) && isset($_GET['date_of_death'])) {
    $stmt = $pdo->prepare("SELECT * FROM mortality_records WHERE animal_id = ? AND date_of_death = ?");
    $stmt->execute([$_GET['animal_id'], $_GET['date_of_death']]);
    $row = $stmt->fetch();
    $id_field = ['animal_id', 'date_of_death'];
    $id_value = [$_GET['animal_id'], $_GET['date_of_death']];
} elseif ($table === 'movement_records' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM movement_records WHERE id = ?");
    $stmt->execute([$_GET['id']]);
    $row = $stmt->fetch();
    $id_field = 'id';
    $id_value = $_GET['id'];
} else {
    echo '<p style="color: red;">Invalid or missing parameters.</p>';
    exit();
}

if (!$row) {
    echo '<p style="color: red;">Record not found.</p>';
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = array_filter($_POST, function($key) use ($id_field) {
            return !in_array($key, (array)$id_field);
        }, ARRAY_FILTER_USE_KEY);
        $set_clause = implode(", ", array_map(function($key) {
            return "$key = ?";
        }, array_keys($data)));
        $params = array_values($data);
        if (is_array($id_value)) {
            $where_clause = implode(" = ? AND ", array_keys(array_flip($id_field))) . " = ?";
            $params = array_merge($params, $id_value);
        } else {
            $where_clause = "$id_field = ?";
            $params[] = $id_value;
        }
        $stmt = $pdo->prepare("UPDATE $table SET $set_clause WHERE $where_clause");
        $stmt->execute($params);
        $message = '<p style="color: green;">Record updated successfully.</p>';
    } catch (PDOException $e) {
        $message = '<p style="color: red;">Error updating record: ' . htmlentities($e->getMessage()) . "</p>\n";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Record</title>
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
    h1 {
      color: #228B22;
      border-bottom: 2px solid #228B22;
      padding-bottom: 10px;
    }
    .form-group { 
      margin-bottom: 15px; 
    }
    label { 
      display: block; 
      font-weight: bold;
      margin-bottom: 5px;
    }
    input, textarea, select { 
      width: 100%; 
      max-width: 400px; 
      padding: 8px; 
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    textarea {
      min-height: 100px;
    }
    button, input[type="submit"] { 
      padding: 10px 20px; 
      background-color: #228B22; 
      color: white; 
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
    }
    button:hover, input[type="submit"]:hover {
      background-color: #1a6b1a;
    }
    .error { 
      color: red; 
      padding: 10px;
      background-color: #ffeeee;
      border: 1px solid #ffcccc;
      border-radius: 4px;
      margin-bottom: 15px;
    }
    .success {
      color: green;
      padding: 10px;
      background-color: #eeffee;
      border: 1px solid #ccffcc;
      border-radius: 4px;
      margin-bottom: 15px;
    }
    .back-link {
      display: inline-block;
      margin-top: 20px;
      color: #228B22;
      text-decoration: none;
    }
    .back-link:hover {
      text-decoration: underline;
    }
    </style>
</head>
<body>
    <header>
        <h1>Edit <?php echo htmlspecialchars($table); ?> Record</h1>
    </header>
    <div class="container">
    <?php echo $message; ?>
    <form method="POST">
    <?php
    $fields = [];
    switch ($table) {
        case 'farmers': $fields = ['id' => $row['id'], 'name' => $row['name'], 'farm_name' => $row['farm_name'], 'contact_number' => $row['contact_number'], 'email' => $row['email']]; break;
        case 'livestock': $fields = ['animal_id' => $row['animal_id'], 'species' => $row['species'], 'date_of_birth' => $row['date_of_birth'], 'breed' => $row['breed']]; break;
        case 'health_records': $fields = ['animal_id' => $row['animal_id'], 'checkup_date' => $row['checkup_date'], 'notes' => $row['notes']]; break;
        case 'breeding_records': $fields = ['animal_id' => $row['animal_id'], 'breeding_date' => $row['breeding_date'], 'sire_id' => $row['sire_id'], 'notes' => $row['notes']]; break;
        case 'feeding_records': $fields = ['animal_id' => $row['animal_id'], 'record_date' => $row['record_date'], 'feed_type' => $row['feed_type'], 'notes' => $row['notes']]; break;
        case 'mortality_records': $fields = ['animal_id' => $row['animal_id'], 'date_of_death' => $row['date_of_death'], 'cause_of_death' => $row['cause_of_death']]; break;
        case 'movement_records': $fields = ['id' => $row['id'], 'animal_id' => $row['animal_id'], 'movement_type' => $row['movement_type'], 'movement_date' => $row['movement_date'], 'notes' => $row['notes']]; break;
    }
    foreach ($fields as $field => $value) {
        echo "<div class='form-group'><label for='$field'>" . ucfirst(str_replace('_', ' ', $field)) . ":</label>";
        $readonly = in_array($field, (array)$id_field) ? 'readonly' : '';
        $inputType = (strpos($field, 'date') !== false) ? 'date' : 'text';
        echo "<input type='$inputType' name='$field' id='$field' value='" . htmlspecialchars($value) . "' $readonly required></div>";
    }
    ?>
    <button type="submit">Update Record</button>
    </form>
    <p><a class="back-link" href="indexx.php">Back to Dashboard</a></p>
    </div>
</body>
</html>