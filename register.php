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
    <title>Daftar - MedReminder</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page active">
        <div class="container">
            <div class="auth-container">
                <div class="logo">✨</div>
                <h1>Buat Akun Baru</h1>
                <p>Daftar untuk mulai mengatur obat Anda</p>

                <?php
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                ?>

                <form action="process_register.php" method="POST" onsubmit="return validatePassword()">
                    <div class="form-group">
                        <label>👤 Nama Lengkap</label>
                        <div class="input-wrapper">
                            <span class="input-icon">👤</span>
                            <input type="text" name="name" id="name" placeholder="John Doe" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>📧 Email</label>
                        <div class="input-wrapper">
                            <span class="input-icon">📧</span>
                            <input type="email" name="email" id="email" placeholder="nama@email.com" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>🔒 Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="password" id="password" placeholder="Minimal 6 karakter" minlength="6" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>🔒 Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <span class="input-icon">🔒</span>
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Ulangi password" minlength="6" required>
                        </div>
                    </div>

                    <button type="submit" class="btn">
                        ✨ Daftar Sekarang
                    </button>
                </form>

                <div class="link-text">
                    Sudah punya akun? <a href="login.php">Masuk di sini</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;
            
            if (password !== confirm) {
                alert('Password tidak cocok!');
                return false;
            }
            
            if (password.length < 6) {
                alert('Password minimal 6 karakter!');
                return false;
            }
            
            return true;
        }
    </script>
</body>
</html>