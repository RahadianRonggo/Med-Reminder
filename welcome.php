<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang - Med Reminder</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --glass-bg: rgba(255, 255, 255, 0.1);
            --glass-border: rgba(255, 255, 255, 0.2);
            --text-color: #fff;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
            background: #1a1a2e; /* Warna gelap elegan */
            height: 100vh;
            overflow: hidden; /* Supaya tidak ada scrollbar */
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* --- BACKGROUND ANIMATION --- */
        .circle {
            position: absolute;
            border-radius: 50%;
            background: var(--primary-gradient);
            filter: blur(80px); /* Efek blur untuk cahaya glow */
            z-index: -1;
            animation: moveLight 10s infinite alternate;
        }

        .circle-1 { width: 300px; height: 300px; top: -50px; left: -50px; }
        .circle-2 { width: 400px; height: 400px; bottom: -100px; right: -100px; background: linear-gradient(135deg, #ff6b6b, #f06595); }

        @keyframes moveLight {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 50px) scale(1.1); }
        }

        /* --- ICON MELAYANG (FLOATING) --- */
        .floating-icon {
            position: absolute;
            color: rgba(255, 255, 255, 0.1); /* Transparan */
            font-size: 4rem;
            z-index: -1;
            animation: float 6s ease-in-out infinite;
        }
        
        .icon-1 { top: 20%; left: 20%; animation-duration: 6s; font-size: 3rem; }
        .icon-2 { top: 70%; left: 15%; animation-duration: 8s; font-size: 5rem; }
        .icon-3 { top: 30%; right: 20%; animation-duration: 7s; font-size: 4rem; }
        .icon-4 { bottom: 15%; right: 25%; animation-duration: 9s; font-size: 2.5rem; }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        /* --- GLASS CARD UTAMA --- */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(15px); /* KUNCI EFEK KACA BURAM */
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 50px 40px;
            text-align: center;
            color: white;
            max-width: 400px;
            width: 90%;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
            transform: translateY(20px);
            opacity: 0;
            animation: slideUp 1s forwards 0.5s; /* Muncul perlahan */
        }

        @keyframes slideUp {
            to { transform: translateY(0); opacity: 1; }
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
        }

        p {
            font-size: 1rem;
            margin-bottom: 30px;
            opacity: 0.8;
            line-height: 1.6;
        }

        .btn-start {
            display: inline-block;
            padding: 15px 40px;
            background: white;
            color: #333;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2);
        }

        .btn-start:hover {
            transform: scale(1.05);
            background: var(--primary-gradient);
            color: white;
            box-shadow: 0 5px 20px rgba(118, 75, 162, 0.6);
        }

        .logo-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            background: -webkit-linear-gradient(#fff, #a29bfe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>
<body>

    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>

    <i class="fas fa-pills floating-icon icon-1"></i>
    <i class="fas fa-heartbeat floating-icon icon-2"></i>
    <i class="fas fa-capsules floating-icon icon-3"></i>
    <i class="fas fa-file-medical floating-icon icon-4"></i>

    <div class="glass-card">
        <div class="logo-icon">
            <i class="fas fa-heart-pulse"></i>
        </div>
        <h1>Med Reminder</h1>
        <p>Jangan biarkan lupa mengganggu kesehatanmu. Pantau jadwal minum obat dengan gaya yang lebih modern.</p>
        
        <a href="login.php" class="btn-start">Mulai Sekarang</a>
    </div>

</body>
</html>