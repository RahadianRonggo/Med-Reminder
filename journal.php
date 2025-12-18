<?php
include 'config.php'; // Pastikan koneksi database jalan
session_start();

// --- 1. OTOMATIS BUAT TABEL (Supaya kamu gak repot di phpMyAdmin) ---
$queryBuatTabel = "CREATE TABLE IF NOT EXISTS health_journal (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    mood VARCHAR(20),
    catatan TEXT,
    tanggal DATETIME DEFAULT CURRENT_TIMESTAMP
)";
mysqli_query($conn, $queryBuatTabel);

// --- 2. PROSES SIMPAN CATATAN ---
if (isset($_POST['submit_journal'])) {
    $user = $_SESSION['username'] ?? 'Tamu'; // Pakai 'Tamu' kalau belum login
    $mood = $_POST['mood'];
    $catatan = $_POST['catatan'];

    $insert = "INSERT INTO health_journal (username, mood, catatan) VALUES ('$user', '$mood', '$catatan')";
    if (mysqli_query($conn, $insert)) {
        echo "<script>alert('Catatan harian berhasil disimpan!'); window.location='journal.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mood & Health Tracker</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            background: #eef2f3;
            font-family: 'Poppins', sans-serif;
        }

        /* Container Kiri (Form) */
        .card-input {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            border: none;
            overflow: hidden;
        }

        .header-gradient {
            background: linear-gradient(135deg, #FF9A9E 0%, #FECFEF 100%);
            padding: 20px;
            color: white;
            text-align: center;
        }

        /* Pilihan Mood (Emoji) */
        .mood-options {
            display: flex;
            justify-content: space-between;
            margin: 20px 0;
        }

        .mood-item input {
            display: none; /* Sembunyikan radio button asli */
        }

        .mood-item label {
            cursor: pointer;
            font-size: 2.5rem;
            transition: transform 0.2s, filter 0.2s;
            filter: grayscale(100%);
            opacity: 0.5;
            display: block;
        }

        /* Efek saat dipilih */
        .mood-item input:checked + label {
            transform: scale(1.3);
            filter: grayscale(0%);
            opacity: 1;
        }

        .mood-item:hover label {
            transform: scale(1.1);
        }

        /* Container Kanan (Timeline) */
        .timeline-area {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .journal-item {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 5px solid #FF9A9E;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
            position: relative;
        }

        .journal-mood-icon {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 2rem;
        }

        .journal-date {
            font-size: 0.8rem;
            color: #999;
        }

        .btn-simpan {
            background: #FF9A9E;
            border: none;
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            color: white;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-simpan:hover { background: #ff7675; }
        
        /* Scrollbar cantik */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-5 mb-4">
            <a href="home.php" class="text-decoration-none text-muted mb-3 d-block">← Kembali ke Dashboard</a>
            
            <div class="card card-input">
                <div class="header-gradient">
                    <h4 class="m-0">Bagaimana kabarmu?</h4>
                    <p class="m-0 small">Catat kondisi kesehatanmu hari ini</p>
                </div>
                <div class="p-4">
                    <form method="POST">
                        <p class="text-center text-muted mb-1">Pilih Mood Kamu:</p>
                        
                        <div class="mood-options">
                            <div class="mood-item">
                                <input type="radio" name="mood" id="m1" value="Senang" required>
                                <label for="m1">😁</label>
                            </div>
                            <div class="mood-item">
                                <input type="radio" name="mood" id="m2" value="Biasa" required>
                                <label for="m2">😐</label>
                            </div>
                            <div class="mood-item">
                                <input type="radio" name="mood" id="m3" value="Sakit" required>
                                <label for="m3">🤒</label>
                            </div>
                            <div class="mood-item">
                                <input type="radio" name="mood" id="m4" value="Lelah" required>
                                <label for="m4">😫</label>
                            </div>
                            <div class="mood-item">
                                <input type="radio" name="mood" id="m5" value="Marah" required>
                                <label for="m5">😡</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Kesehatan</label>
                            <textarea name="catatan" class="form-control" rows="4" placeholder="Contoh: Habis minum obat, kepala agak pusing tapi badan mulai enakan..."></textarea>
                        </div>

                        <button type="submit" name="submit_journal" class="btn btn-simpan">Simpan Jurnal</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <h4 class="mb-3 text-secondary">Riwayat Kesehatan</h4>
            
            <div class="timeline-area">
                <?php
                // Ambil data jurnal dari database
                $user = $_SESSION['username'] ?? 'Tamu';
                $query = mysqli_query($conn, "SELECT * FROM health_journal WHERE username='$user' ORDER BY tanggal DESC");

                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        // Tentukan Icon berdasarkan text database
                        $icon = "😐";
                        if($row['mood'] == 'Senang') $icon = "😁";
                        if($row['mood'] == 'Sakit') $icon = "🤒";
                        if($row['mood'] == 'Lelah') $icon = "😫";
                        if($row['mood'] == 'Marah') $icon = "😡";
                        
                        // Format Tanggal
                        $tgl = date('d M Y, H:i', strtotime($row['tanggal']));
                ?>
                
                <div class="journal-item">
                    <div class="journal-mood-icon"><?php echo $icon; ?></div>
                    <h5 class="mb-1 text-primary"><?php echo $row['mood']; ?></h5>
                    <div class="journal-date mb-2"><i class="far fa-clock"></i> <?php echo $tgl; ?></div>
                    <p class="mb-0 text-dark"><?php echo nl2br(htmlspecialchars($row['catatan'])); ?></p>
                </div>

                <?php 
                    }
                } else {
                    echo "<div class='text-center text-muted mt-5'><p>Belum ada catatan. Ayo tulis jurnal pertamamu!</p></div>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>