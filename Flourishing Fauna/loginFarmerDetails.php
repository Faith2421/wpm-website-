<?php 
include 'includes/header.php'; 
include 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT user_id, username, password, is_admin FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            // Update last login
            $update_stmt = $conn->prepare("UPDATE users SET last_login = NOW() WHERE user_id = ?");
            $update_stmt->bind_param("i", $user['user_id']);
            $update_stmt->execute();
            $update_stmt->close();
            
            $_SESSION['success'] = "Login successful!";
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Username not found";
    }
    $stmt->close();
}
?>

