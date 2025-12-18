<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user_id = $_SESSION['user_id'];
    $med_id = (int)$_POST['med_id'];
    $current_status = (int)$_POST['current_status'];
    
    // Toggle: jika 0 jadi 1, jika 1 jadi 0
    $new_status = ($current_status == 0) ? 1 : 0;
    
    $stmt = $conn->prepare("UPDATE medications SET taken = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param("iii", $new_status, $med_id, $user_id);
    
    if ($stmt->execute()) {
        if ($new_status == 1) {
            $_SESSION['success'] = "Obat ditandai sudah diminum!";
        } else {
            $_SESSION['success'] = "Obat ditandai belum diminum!";
        }
    } else {
        $_SESSION['error'] = "Gagal mengupdate status: " . $conn->error;
    }
    
    $stmt->close();
    header("Location: home.php");
    exit();
    
} else {
    header("Location: home.php");
    exit();
}

$conn->close();
?>