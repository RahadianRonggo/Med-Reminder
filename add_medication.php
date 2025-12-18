<?php
/**
 * FILE: add_medication.php
 * FUNGSI: Memproses penambahan obat baru
 * 
 * PENJELASAN:
 * - Menerima data dari form tambah obat
 * - Insert data ke tabel medications
 * - Foreign key user_id = menghubungkan obat dengan user yang login
 */

session_start();
require_once 'config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $user_id = $_SESSION['user_id'];
    $name = trim(htmlspecialchars($_POST['name']));
    $dose = trim(htmlspecialchars($_POST['dose']));
    $time = trim($_POST['time']);
    $frequency = trim(htmlspecialchars($_POST['frequency']));
    $notes = trim(htmlspecialchars($_POST['notes']));
    
    // Validasi: Pastikan field wajib terisi
    if (empty($name) || empty($dose) || empty($time) || empty($frequency)) {
        $_SESSION['error'] = "Nama obat, dosis, waktu, dan frekuensi harus diisi!";
        header("Location: home.php");
        exit();
    }
    
    /**
     * INSERT MEDICATION
     * - user_id = ID user yang sedang login
     * - taken = 0 (default belum diminum)
     * - "iissss" = Integer, Integer, String, String, String, String
     */
    $stmt = $conn->prepare("INSERT INTO medications (user_id, name, dose, time, frequency, notes, taken) VALUES (?, ?, ?, ?, ?, ?, 0)");
    $stmt->bind_param("isssss", $user_id, $name, $dose, $time, $frequency, $notes);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "🎉 Obat berhasil ditambahkan!";
    } else {
        $_SESSION['error'] = "Gagal menambahkan obat: " . $conn->error;
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