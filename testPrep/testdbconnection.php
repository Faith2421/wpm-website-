 <?php
// Save this as test.php and run it
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "livestock_management";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Database connection successful!<br>";
    
    // Test query
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();
    
    echo "📋 Found " . count($tables) . " tables:<br>";
    foreach($tables as $table) {
        echo "- " . $table[0] . "<br>";
    }
    
} catch(PDOException $e) {
    echo " Connection failed: " . $e->getMessage();
}
?>  