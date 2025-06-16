<?php

require_once "pdo.php";

// Check database connection
if ($pdo === null) {
    $_SESSION['error'] = "Database connection failed";
    header("Location: indexx.php");
    exit;
}

$table = $_GET['table'] ?? 'farmers';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Filter out submit button and password confirmation from POST data
        $data = array_filter($_POST, function($key) { 
            return $key !== 'submit' && $key !== 'password_confirm'; 
        }, ARRAY_FILTER_USE_KEY);
        
        // Special handling for password fields
        if ($table === 'farmers' && isset($data['password'])) {
            // Verify password confirmation
            if (empty($_POST['password_confirm']) || $data['password'] !== $_POST['password_confirm']) {
                throw new Exception("Password and confirmation don't match");
            }
            // Hash the password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Prepare SQL statement
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        
        $stmt = $pdo->prepare("INSERT INTO $table ($columns) VALUES ($placeholders)");
        $stmt->execute(array_values($data));
        
        $_SESSION['success'] = "Record added successfully to $table";
        header("Location: indexx.php");
        exit();
        
    } catch (Exception $e) {
        $_SESSION['error'] = "Error adding record: " . $e->getMessage();
        header("Location: add.php?table=$table");
        exit();
    }
}

// Define fields for each table
$fields = match($table) {
    'farmers' => [
        'name' => '', 
        'farm_name' => '', 
        'contact_number' => '', 
        'email' => '',
        'password' => ''
    ],
    'livestock' => [
        'animal_id' => '', 
        'species' => '', 
        'date_of_birth' => '', 
        'breed' => ''
    ],
    'health_records' => [
        'animal_id' => '', 
        'checkup_date' => '', 
        'notes' => ''
    ],
    'breeding_records' => [
        'animal_id' => '', 
        'breeding_date' => '', 
        'sire_id' => '', 
        'notes' => ''
    ],
    'feeding_records' => [
        'animal_id' => '', 
        'record_date' => '', 
        'feed_type' => '', 
        'notes' => ''
    ],
    'mortality_records' => [
        'animal_id' => '', 
        'date_of_death' => '', 
        'cause_of_death' => ''
    ],
    'movement_records' => [
        'animal_id' => '', 
        'movement_type' => '', 
        'movement_date' => '', 
        'notes' => ''
    ],
    default => null
};

// Validate table
if ($fields === null) {
    $_SESSION['error'] = "Invalid table specified";
    header("Location: indexx.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Record to <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $table))) ?></title>
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
    <div class="container">
        <h1>Add New <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $table))) ?> Record</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <form method="POST">
            <?php foreach ($fields as $field => $value): ?>
                <div class="form-group">
                    <label for="<?= $field ?>"><?= ucfirst(str_replace('_', ' ', $field)) ?>:</label>
                    
                    <?php
                    // Determine input type
                    $inputType = match(true) {
                        str_contains($field, 'date') => 'date',
                        $field === 'password' => 'password',
                        $field === 'email' => 'email',
                        $field === 'notes' => 'textarea',
                        default => 'text'
                    };
                    
                    if ($inputType === 'textarea'): ?>
                        <textarea name="<?= $field ?>" id="<?= $field ?>" required><?= htmlspecialchars($value) ?></textarea>
                    <?php else: ?>
                        <input type="<?= $inputType ?>" name="<?= $field ?>" id="<?= $field ?>" value="<?= htmlspecialchars($value) ?>" required>
                    <?php endif; ?>
                </div>
                
                <?php if ($table === 'farmers' && $field === 'password'): ?>
                    <div class="form-group">
                        <label for="password_confirm">Confirm Password:</label>
                        <input type="password" name="password_confirm" id="password_confirm" required>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <button type="submit" name="submit">Add Record</button>
            <a href="indexx.php" class="back-link">← Back to Dashboard</a>
        </form>
    </div>
</body>
</html>