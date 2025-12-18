<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $email = trim(htmlspecialchars($_POST['email']));
    $password = trim($_POST['password']);
    
    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "Email dan password harus diisi!";
        header("Location: login.php");
        exit();
    }
    
    $stmt = $conn->prepare("SELECT id, name, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];
            
            header("Location: home.php");
            exit();
            
        } else {
            $_SESSION['error'] = "Password salah!";
            header("Location: login.php");
            exit();
        }
        
    } else {
        $_SESSION['error'] = "Email tidak terdaftar!";
        header("Location: login.php");
        exit();
    }
    
    $stmt->close();
    
} else {
    header("Location: login.php");
    exit();
}

$conn->close();
?>