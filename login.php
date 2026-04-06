<?php
session_start();

// Inisialisasi variabel error agar tidak muncul pesan "Undefined variable"
$error = '';

// Di halaman login, kita mengecek apakah user SUDAH login.
// Kalau sudah login, langsung lempar ke index!
if (isset($_SESSION['sudah_login']) && $_SESSION['sudah_login'] === true) {
    header("Location: index.php"); 
    exit;
}

// Panggil koneksi database
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Menangkap inputan form
    $email_nip = trim($_POST['email_nip']);
    $password = $_POST['password'];

    try {
        // Cek ke database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email_nip = :input LIMIT 1");
        $stmt->execute([':input' => $email_nip]);
        $user = $stmt->fetch();

        // 🟢 DIPERBAIKI: Menggunakan password_verify untuk mengecek password yang di-enkripsi
        if ($user && password_verify($password, $user['password'])) { 
            
            // Set session login (Tiket masuk!)
            $_SESSION['sudah_login'] = true; 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama'] = $user['nama_lengkap'];
            $_SESSION['peran'] = $user['peran']; 
            
            // Arahkan ke dashboard utama
            header("Location: index.php");
            exit;
        } else {
            $error = "Email/NIP atau Password salah!";
        }
    } catch (PDOException $e) {
        $error = "Terjadi kesalahan sistem: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MedisDigital+</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --teal: #0a8f7f;
            --teal-dark: #076d60;
            --teal-light: #e0f5f2;
            --teal-glow: rgba(10,143,127,0.18);
            --navy: #0d1f2d;
            --navy-mid: #162b3a;
            --slate: #8da5b3;
            --white: #ffffff;
            --off-white: #f5f9fb;
            --border: rgba(255,255,255,0.08);
            --error: #ff6b6b;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background-color: var(--navy);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            overflow: hidden;
        }

        /* Animated background */
        .bg-decor {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
        }
        .bg-decor::before {
            content: '';
            position: absolute;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(10,143,127,0.2) 0%, transparent 70%);
            top: -150px; left: -150px;
            animation: pulse-slow 8s ease-in-out infinite;
        }
        .bg-decor::after {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(10,143,127,0.12) 0%, transparent 70%);
            bottom: -100px; right: -100px;
            animation: pulse-slow 8s ease-in-out infinite reverse;
        }
        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 1; }
        }

        /* Grid dots */
        .grid-dots {
            position: fixed; inset: 0; z-index: 0;
            background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        .card {
            position: relative; z-index: 10;
            background: rgba(22, 43, 58, 0.85);
            backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 40px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(10,143,127,0.1);
            animation: card-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes card-in {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-wrap {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 2rem;
        }
        .logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--teal), #0cc4ae);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 20px var(--teal-glow);
        }
        .logo-icon svg { width: 22px; height: 22px; color: white; }
        .logo-text { font-family: 'Sora', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--white); }
        .logo-text span { color: #0cc4ae; }

        h2 { font-family: 'Sora', sans-serif; font-size: 1.5rem; font-weight: 600; color: var(--white); margin-bottom: 0.4rem; }
        .subtitle { font-size: 0.875rem; color: var(--slate); margin-bottom: 1.75rem; }

        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: 0.8rem; font-weight: 500; color: #9ab5c4; margin-bottom: 0.5rem; letter-spacing: 0.04em; text-transform: uppercase; }

        .input-wrap { position: relative; }
        .input-wrap svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--slate); pointer-events: none; transition: color 0.2s; }

        input[type=text], input[type=email], input[type=password], input[type=number], input[type=tel], select, textarea {
            width: 100%;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            padding: 0.75rem 1rem 0.75rem 2.75rem;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--white);
            outline: none;
            transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--teal);
            background: rgba(10,143,127,0.06);
            box-shadow: 0 0 0 3px var(--teal-glow);
        }
        input:focus + svg, select:focus + svg { color: var(--teal); }
        input::placeholder { color: rgba(141,165,179,0.5); }

        /* Toggle password */
        .toggle-pw {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 0;
            color: var(--slate);
        }
        .toggle-pw svg { width: 18px; height: 18px; }

        .row-inline { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; }
        .checkbox-label { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; color: var(--slate); cursor: pointer; }
        .checkbox-label input[type=checkbox] {
            width: 16px; height: 16px;
            accent-color: var(--teal);
            padding: 0; border-radius: 4px;
        }
        .link { font-size: 0.85rem; color: #0cc4ae; text-decoration: none; font-weight: 500; }
        .link:hover { text-decoration: underline; }

        .btn-primary {
            width: 100%;
            background: linear-gradient(135deg, var(--teal), #0cc4ae);
            color: white;
            border: none; border-radius: 12px;
            padding: 0.875rem;
            font-family: 'Sora', sans-serif;
            font-size: 0.95rem; font-weight: 600;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(10,143,127,0.35);
            transition: transform 0.15s, box-shadow 0.15s, opacity 0.15s;
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 32px rgba(10,143,127,0.45); }
        .btn-primary:active { transform: translateY(0); }

        .divider { border: none; border-top: 1px solid rgba(255,255,255,0.07); margin: 1.5rem 0; }

        .footer-text { text-align: center; font-size: 0.85rem; color: var(--slate); }
        .footer-text a { color: #0cc4ae; font-weight: 500; text-decoration: none; }
        .footer-text a:hover { text-decoration: underline; }

        select option { background: var(--navy-mid); }
        textarea { padding-top: 0.75rem; resize: vertical; min-height: 80px; }
    </style>
</head>
<body>
<div class="bg-decor"></div>
<div class="grid-dots"></div>

<div class="card">
    <div class="logo-wrap">
        <div class="logo-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div class="logo-text">Medis<span>Digital+</span></div>
    </div>
    <h2>Selamat Datang</h2>
    <p class="subtitle">Masuk ke sistem rekam medis digital Anda</p>

    <?php if ($error != ''): ?>
        <div style="background: var(--error); color: white; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.85rem; text-align: center;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <div class="form-group">
            <label>Email / NIP</label>
            <div class="input-wrap">
                <input type="text" name="email_nip" placeholder="email@klinik.id atau NIP" required>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
            </div>
        </div>
        <div class="form-group">
            <label>Password</label>
            <div class="input-wrap">
                <input type="password" name="password" id="pw" placeholder="Masukkan password" required>
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <button type="button" class="toggle-pw" onclick="togglePw()">
                    <svg id="eye-icon" fill="none" stroke=\"currentColor\" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </button>
            </div>
        </div>
        <div class="row-inline">
            <label class="checkbox-label">
                <input type="checkbox" name="remember"> Ingat saya
            </label>
            <a href="lupa-password.php" class="link">Lupa password?</a>
        </div>
        <button type="submit" class="btn-primary">Masuk ke Sistem</button>
    </form>

    <hr class="divider">
    <p class="footer-text">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
</div>

<script>
function togglePw() {
    const inp = document.getElementById('pw');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
