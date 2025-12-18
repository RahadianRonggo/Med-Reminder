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
    
    $stmt = $conn->prepare("DELETE FROM medications WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $med_id, $user_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['success'] = "Obat berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Obat tidak ditemukan atau Anda tidak memiliki akses!";
        }
    } else {
        $_SESSION['error'] = "Gagal menghapus obat: " . $conn->error;
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