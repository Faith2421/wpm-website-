 <?php
require_once 'config.php';

$animalId = isset($_POST['animalID']) ? trim($_POST['animalID']) : '';
$feedType = isset($_POST['feedType']) ? trim($_POST['feedType']) : '';
$quantity = isset($_POST['quantity']) ? trim($_POST['quantity']) : '';
$nutritionalInfo = isset($_POST['nutritionalInfo']) ? trim($_POST['nutritionalInfo']) : '';
$feedingRecordDate = isset($_POST['feedingRecordDate']) ? $_POST['feedingRecordDate'] : date('Y-m-d');
$feedingScheduleNotes = isset($_POST['feedingScheduleNotes']) ? trim($_POST['feedingScheduleNotes']) : '';

try {
    $pdo->beginTransaction();

    if (empty($animalId)) {
        throw new Exception('Animal ID is required.');
    }

    if (empty($feedType)) {
        throw new Exception('Feed type is required.');
    }

    if (!isValidDate($feedingRecordDate)) {
        throw new Exception('Invalid feeding record date format. Use YYYY-MM-DD.');
    }

    $stmt = $pdo->prepare("INSERT INTO feeding_records (animal_id, record_date, feed_type, quantity, nutritional_info, notes) 
        VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$animalId, $feedingRecordDate, $feedType, $quantity ?: null, $nutritionalInfo ?: null, $feedingScheduleNotes ?: null]);

    $pdo->commit();

    echo json_encode([
        'status' => 'success',
        'message' => 'Feeding record added successfully',
        'animal_id' => $animalId
    ]);

} catch (PDOException $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Database error',
        'details' => $e->getMessage()
    ]);
} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Validation error',
        'details' => $e->getMessage()
    ]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Back to Home</title>
    <style>
        .back-link {
            display: inline-block;
            padding: 5px 10px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <a href="indexx.php" class="back-link">← Back to Dashboard</a>
</body>
</html>