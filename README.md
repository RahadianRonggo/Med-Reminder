![Tampilan Website](review.png)
Fitur Utama
Aplikasi MedReminder dirancang untuk membantu pengguna mengelola jadwal minum obat secara disiplin dengan fitur-fitur berikut:

Manajemen Obat Fleksibel: Pengguna dapat menambahkan, mengedit, dan menghapus daftar obat sesuai kebutuhan (Custom Medication List).

Sistem Login & Registrasi: Keamanan data pribadi pengguna dengan sistem akun yang terintegrasi database.

Dashboard Pemantauan: Pantau total obat yang harus diminum, progres harian, dan jumlah obat yang sudah dikonsumsi secara real-time.

Notifikasi Suara: Dilengkapi dengan alarm suara otomatis untuk mengingatkan pengguna tepat pada waktunya.

Status: Fitur untuk menandai status obat (misal: Wajib, Rutin, atau Lunas jika berkaitan dengan stok).


Cara Instalasi (Untuk Pengguna/Penguji)
Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi di lingkungan lokal (localhost):

Download Source Code: Unduh file proyek ini (Klik tombol Code > Download ZIP) dan ekstrak foldernya.

Pindahkan Folder: Letakkan folder hasil ekstrak ke dalam direktori server lokal Anda, contohnya di C:\xampp\htdocs\medreminder.

Aktifkan Server: Jalankan aplikasi XAMPP dan pastikan modul Apache serta MySQL dalam status Start.

Persiapan Database:

Buka browser dan akses localhost/phpmyadmin.

Buat database baru dengan nama: db_medreminder.

Import Data:

Klik pada database db_medreminder yang baru dibuat.

Pilih menu Import, lalu pilih file db_medreminder.sql yang tersedia di dalam folder proyek.

Klik Go atau Import dan tunggu hingga proses selesai.

Konfigurasi Koneksi: Pastikan pengaturan database pada file koneksi.php sudah sesuai dengan username dan password MySQL Anda.

Jalankan Aplikasi: Buka browser dan akses URL: localhost/medreminder
