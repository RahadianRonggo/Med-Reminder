<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

$stmt = $conn->prepare("SELECT * FROM medications WHERE user_id = ? ORDER BY time ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$medications = $stmt->get_result();

$total_meds = $medications->num_rows;
$taken_count = 0;
$today_count = 0;

$meds_array = [];
while ($med = $medications->fetch_assoc()) {
    $meds_array[] = $med;
    if ($med['taken'] == 1) {
        $taken_count++;
    } else {
        $today_count++;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MedReminder</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="page active">
        <div class="container">
            <div class="header">
                <div class="header-content">
                    <div class="user-info">
                        <h2>Halo, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
                        <p>Semoga sehat selalu</p>
                    </div>
                    <a href="logout.php" class="logout-btn">🚪 Keluar</a>
                </div>
            </div>

            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-number"><?php echo $total_meds; ?></div>
                    <div class="stat-label">Total Obat</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $today_count; ?></div>
                    <div class="stat-label">Hari Ini</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number"><?php echo $taken_count; ?></div>
                    <div class="stat-label">Sudah Minum</div>
                </div>
            </div>

            <div class="content">
                <div class="tabs">
                    <button class="tab active" onclick="switchTab('home')">🏠 Beranda</button>
                    <button class="tab" onclick="switchTab('add')">➕ Tambah</button>
                </div>

                <?php
                if (isset($_SESSION['success'])) {
                    echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                    unset($_SESSION['success']);
                }
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-error">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']);
                }
                ?>

                <div class="tab-content active" id="homeTab">
                    <div class="notification-status" id="notifStatus">
                        <div>
                            <strong style="font-size: 15px;">⚠️ Status Notifikasi</strong>
                            <p style="font-size: 13px; margin-top: 5px; opacity: 0.8;" id="notifText">Aktifkan untuk pengingat otomatis</p>
                        </div>
                        <button type="button" class="btn" style="width: auto; padding: 10px 20px;" id="notifBtn" onclick="enableNotifications()">Aktifkan</button>
                    </div>

                    <div class="medication-list">
                        <h2>📋 Jadwal Obat Anda</h2>
                        
                        <?php if (count($meds_array) > 0): ?>
                            <?php foreach ($meds_array as $med): ?>
                                <div class="med-item <?php echo $med['taken'] ? 'taken' : ''; ?>">
                                    <div class="med-info">
                                        <h3>
                                            <?php echo $med['taken'] ? '✅' : '💊'; ?>
                                            <?php echo htmlspecialchars($med['name']); ?>
                                            <span class="badge <?php echo $med['taken'] ? 'badge-success' : 'badge-primary'; ?>">
                                                <?php echo $med['taken'] ? 'Sudah Minum' : 'Belum'; ?>
                                            </span>
                                        </h3>
                                        <p>📊 Dosis: <?php echo htmlspecialchars($med['dose']); ?></p>
                                        <p>🔄 <?php echo htmlspecialchars($med['frequency']); ?></p>
                                        <?php if (!empty($med['notes'])): ?>
                                            <p style="font-style: italic; margin-top: 6px;">
                                                📝 <?php echo htmlspecialchars($med['notes']); ?>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="med-actions">
                                        <div class="med-time">
                                            <?php echo date('H:i', strtotime($med['time'])); ?>
                                        </div>
                                        <form action="toggle_medication.php" method="POST" style="display: inline;">
                                            <input type="hidden" name="med_id" value="<?php echo $med['id']; ?>">
                                            <input type="hidden" name="current_status" value="<?php echo $med['taken']; ?>">
                                            <button type="submit" class="btn btn-check">
                                                <?php echo $med['taken'] ? '↩️' : '✓'; ?>
                                            </button>
                                        </form>
                                        <form action="delete_medication.php" method="POST" style="display: inline;" onsubmit="return confirm('Yakin ingin menghapus obat ini?')">
                                            <input type="hidden" name="med_id" value="<?php echo $med['id']; ?>">
                                            <button type="submit" class="btn btn-danger">🗑️</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p>Belum ada jadwal obat</p>
                                <p style="font-size: 13px; margin-top: 8px; opacity: 0.7;">Tambahkan obat pertama Anda!</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="tab-content" id="addTab">
                    <form action="add_medication.php" method="POST">
                        <div class="form-group">
                            <label>💊 Nama Obat</label>
                            <input type="text" name="name" placeholder="Contoh: Paracetamol 500mg" required>
                        </div>

                        <div class="form-group">
                            <label>📊 Dosis</label>
                            <input type="text" name="dose" placeholder="Contoh: 1 tablet" required>
                        </div>

                        <div class="form-group">
                            <label>⏰ Waktu Minum</label>
                            <input type="time" name="time" required>
                        </div>

                        <div class="form-group">
                            <label>🔄 Frekuensi</label>
                            <select name="frequency">
                                <option value="Setiap hari">Setiap Hari</option>
                                <option value="2x sehari">2x Sehari</option>
                                <option value="3x sehari">3x Sehari</option>
                                <option value="Seminggu sekali">Seminggu Sekali</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>📝 Catatan</label>
                            <textarea name="notes" placeholder="Opsional"></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">
                            ✨ Tambah Pengingat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Switch Tab Function
    function switchTab(tab) {
        console.log('Switching to:', tab);
        
        const tabs = document.querySelectorAll('.tab');
        const contents = document.querySelectorAll('.tab-content');
        
        tabs.forEach(t => t.classList.remove('active'));
        contents.forEach(c => c.classList.remove('active'));
        
        if (tab === 'home') {
            tabs[0].classList.add('active');
            document.getElementById('homeTab').classList.add('active');
        } else if (tab === 'add') {
            tabs[1].classList.add('active');
            document.getElementById('addTab').classList.add('active');
        }
    }

    // Enable Notifications Function
    function enableNotifications() {
        console.log("Enable notifications clicked");
        
        if (!("Notification" in window)) {
            alert("Browser tidak mendukung notifikasi");
            return;
        }

        if (Notification.permission === "granted") {
            console.log("Already granted");
            updateNotificationUI(true);
            showTestNotification();
            return;
        }

        if (Notification.permission === "denied") {
            alert("Notifikasi diblokir! Aktifkan di pengaturan browser.");
            return;
        }

        Notification.requestPermission().then(function(permission) {
            console.log("Permission:", permission);
            
            if (permission === "granted") {
                updateNotificationUI(true);
                showTestNotification();
            } else {
                alert("Anda menolak izin notifikasi");
            }
        });
    }

    // Update Notification UI
    function updateNotificationUI(enabled) {
        const notifStatus = document.getElementById('notifStatus');
        const notifText = document.getElementById('notifText');
        const notifBtn = document.getElementById('notifBtn');
        
        if (enabled) {
            notifStatus.classList.add('enabled');
            notifText.textContent = 'Notifikasi aktif';
            notifBtn.textContent = '✓ Aktif';
            notifBtn.disabled = true;
            notifBtn.style.opacity = '0.6';
        }
    }

    // Show Test Notification
    function showTestNotification() {
        try {
            const notification = new Notification("MedReminder Aktif!", {
                body: "Notifikasi pengingat telah diaktifkan",
                icon: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='75'>💊</text></svg>"
            });
            
            setTimeout(() => notification.close(), 5000);
            
            notification.onclick = function() {
                window.focus();
                this.close();
            };
        } catch (error) {
            console.error("Error showing notification:", error);
        }
    }

    // Check on page load
    window.addEventListener('DOMContentLoaded', function() {
        console.log("Page loaded");
        
        if ("Notification" in window && Notification.permission === "granted") {
            updateNotificationUI(true);
        }
    });

    // Medications data
    const medications = <?php echo json_encode($meds_array); ?>;
    console.log("Medications loaded:", medications.length);

    // Check medications schedule
    function checkMedicationSchedule() {
        if (Notification.permission !== "granted") return;
        
        const now = new Date();
        const currentTime = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
        
        medications.forEach(function(med) {
            if (med.time === currentTime && med.taken == 0) {
                console.log("Medication reminder:", med.name);
                
                try {
                    new Notification("Waktunya Minum Obat!", {
                        body: med.name + " - " + med.dose,
                        icon: "data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='75'>💊</text></svg>",
                        requireInteraction: true
                    });
                } catch (error) {
                    console.error("Error:", error);
                }
            }
        });
    }

    // Run check every minute
    if (Notification.permission === "granted") {
        setInterval(checkMedicationSchedule, 60000);
    }
    </script>
</body>
</html>
<?php
$stmt->close();
$conn->close();
?>