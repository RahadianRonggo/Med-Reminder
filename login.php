<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - MedReminder</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page active">
        <div class="container">
            <div class="auth-container">
                <div class="logo">💊</div>
                <h1>Selamat Datang!</h1>
                <p>Masuk ke akun MedReminder Anda</p>

                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                ?>

                <form action="process_login.php" method="POST">
                    <div class="form-group">
                        <label>📧 Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">📧</span>
                            <input type="email" name="email" placeholder="nama@email.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>🔒 Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="password" placeholder="Masukkan password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn">
                        🚀 Masuk Sekarang
                    </button>
                </form>

                <div class="link-text">
                    Belum punya akun? <a href="register.php">Daftar sekarang</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>