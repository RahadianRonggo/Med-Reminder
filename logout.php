<?php
/**
 * FILE: logout.php
 * FUNGSI: Logout user dan hapus session
 * 
 * PENJELASAN:
 * - session_destroy() = Menghapus semua data session
 * - unset() = Menghapus variabel session tertentu
 * - Redirect ke login setelah logout
 */

session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke login dengan pesan
session_start(); // Start lagi untuk set pesan
$_SESSION['success'] = "👋 Anda telah logout. Sampai jumpa!";

header("Location: login.php");
exit();
?>