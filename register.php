<?php
session_start();
require_once 'koneksi.php'; // Panggil koneksi database

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama_lengkap']);
    $email_nip = trim($_POST['email_nip']);
    $peran = $_POST['peran'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi_password'];

    // 1. Validasi apakah password dan konfirmasi sama
    if ($password !== $konfirmasi) {
        $error = "Password dan Konfirmasi Password tidak cocok!";
    } else {
        try {
            // 2. Cek apakah email/NIP sudah terdaftar sebelumnya
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email_nip = :email_nip LIMIT 1");
            $stmt->execute([':email_nip' => $email_nip]);
            
            if ($stmt->fetch()) {
                $error = "Email atau NIP sudah terdaftar! Silakan gunakan yang lain.";
            } else {
                // 3. Enkripsi (Hash) Password demi keamanan
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // 4. Simpan ke database
                $sql = "INSERT INTO users (nama_lengkap, email_nip, peran, password) 
                        VALUES (:nama, :email_nip, :peran, :password)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':nama' => $nama,
                    ':email_nip' => $email_nip,
                    ':peran' => $peran,
                    ':password' => $hashed_password
                ]);
                
                // 5. Buat notifikasi sukses dan arahkan ke halaman login
                $_SESSION['sukses_register'] = "Akun berhasil dibuat! Silakan login menggunakan Email/NIP Anda.";
                header("Location: login.php");
                exit;
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — MedisDigital+</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --teal: #0a8f7f; --teal-dark: #076d60; --teal-glow: rgba(10,143,127,0.18);
            --navy: #0d1f2d; --navy-mid: #162b3a; --slate: #8da5b3; --white: #ffffff;
            --error: #ef4444; /* Tambahan warna error */
        }
        body { font-family: 'DM Sans', sans-serif; background-color: var(--navy); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 1.5rem; overflow-x: hidden; }
        .bg-decor { position: fixed; inset: 0; pointer-events: none; z-index: 0; overflow: hidden; }
        .bg-decor::before { content: ''; position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(10,143,127,0.18) 0%, transparent 65%); top: -200px; right: -200px; animation: pulse-slow 9s ease-in-out infinite; }
        .bg-decor::after { content: ''; position: absolute; width: 400px; height: 400px; background: radial-gradient(circle, rgba(10,143,127,0.1) 0%, transparent 65%); bottom: -150px; left: -100px; animation: pulse-slow 9s ease-in-out infinite reverse; }
        @keyframes pulse-slow { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.2); } }
        .grid-dots { position: fixed; inset: 0; z-index: 0; background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px); background-size: 32px 32px; }
        .card { position: relative; z-index: 10; background: rgba(22,43,58,0.88); backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.07); border-radius: 24px; padding: 2.5rem; width: 100%; max-width: 460px; box-shadow: 0 40px 80px rgba(0,0,0,0.4); animation: card-in 0.6s cubic-bezier(0.16,1,0.3,1) both; }
        @keyframes card-in { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        .logo-wrap { display: flex; align-items: center; gap: 10px; margin-bottom: 0.5rem; }
        .logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, var(--teal), #0cc4ae); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px var(--teal-glow); }
        .logo-icon svg { width: 22px; height: 22px; color: white; }
        .logo-text { font-family: 'Sora', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--white); }
        .logo-text span { color: #0cc4ae; }
        h2 { font-family: 'Sora', sans-serif; font-size: 1.4rem; font-weight: 600; color: var(--white); margin: 1.25rem 0 0.3rem; }
        .subtitle { font-size: 0.875rem; color: var(--slate); margin-bottom: 1.75rem; }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .col-span-2 { grid-column: span 2; }
        .form-group { margin-bottom: 0; }
        label { display: block; font-size: 0.78rem; font-weight: 500; color: #9ab5c4; margin-bottom: 0.45rem; letter-spacing: 0.04em; text-transform: uppercase; }
        .input-wrap { position: relative; }
        .input-wrap svg.ico { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; color: var(--slate); pointer-events: none; }
        input, select { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 0.7rem 1rem 0.7rem 2.6rem; font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: var(--white); outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s; appearance: none; }
        input:focus, select:focus { border-color: var(--teal); background: rgba(10,143,127,0.06); box-shadow: 0 0 0 3px var(--teal-glow); }
        input::placeholder { color: rgba(141,165,179,0.45); }
        select option { background: var(--navy-mid); }
        .toggle-pw { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--slate); padding: 0; }
        .toggle-pw svg { width: 17px; height: 17px; }
        .pw-strength { display: flex; gap: 4px; margin-top: 6px; }
        .pw-bar { height: 3px; flex: 1; border-radius: 99px; background: rgba(255,255,255,0.08); transition: background 0.3s; }
        .pw-bar.active-weak { background: #ff6b6b; }
        .pw-bar.active-ok { background: #fbbf24; }
        .pw-bar.active-good { background: #34d399; }
        .btn-primary { width: 100%; background: linear-gradient(135deg, var(--teal), #0cc4ae); color: white; border: none; border-radius: 12px; padding: 0.875rem; font-family: 'Sora', sans-serif; font-size: 0.95rem; font-weight: 600; cursor: pointer; margin-top: 1.5rem; box-shadow: 0 8px 24px rgba(10,143,127,0.35); transition: transform 0.15s, box-shadow 0.15s; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 32px rgba(10,143,127,0.45); }
        .divider { border: none; border-top: 1px solid rgba(255,255,255,0.07); margin: 1.5rem 0; }
        .footer-text { text-align: center; font-size: 0.85rem; color: var(--slate); }
        .footer-text a { color: #0cc4ae; font-weight: 500; text-decoration: none; }
        .footer-text a:hover { text-decoration: underline; }
        .select-wrap { position: relative; }
        .select-wrap::after { content: ''; position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 0; height: 0; border-left: 5px solid transparent; border-right: 5px solid transparent; border-top: 5px solid var(--slate); pointer-events: none; }
        .select-wrap svg.ico { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; color: var(--slate); pointer-events: none; }
        select { padding-left: 2.6rem; }
        @media (max-width: 480px) { .grid-2 { grid-template-columns: 1fr; } .col-span-2 { grid-column: span 1; } }

        /* Style untuk alert error */
        .alert-error { background-color: rgba(239, 68, 68, 0.2); border: 1px solid var(--error); color: #f87171; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 0.85rem; text-align: center; }
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
    <h2>Buat Akun Baru</h2>
    <p class="subtitle">Daftarkan akun untuk staf atau dokter klinik</p>

    <?php if ($error != ''): ?>
        <div class="alert-error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="grid-2">
            <div class="form-group col-span-2">
                <label>Nama Lengkap</label>
                <div class="input-wrap">
                    <input type="text" name="nama_lengkap" placeholder="dr. Andi Setiawan" required>
                    <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
            </div>
            <div class="form-group">
                <label>Email / NIP</label>
                <div class="input-wrap">
                    <input type="text" name="email_nip" placeholder="email@klinik.id" required>
                    <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
            </div>
            <div class="form-group">
                <label>Peran / Jabatan</label>
                <div class="select-wrap">
                    <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <select name="peran" required>
                        <option value="Dokter Umum">Dokter Umum</option>
                        <option value="Perawat / Bidan">Perawat / Bidan</option>
                        <option value="Admin / Resepsionis">Admin / Resepsionis</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <input type="password" name="password" id="pw1" placeholder="Min. 8 karakter" required oninput="checkStrength(this.value)">
                    <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <button type="button" class="toggle-pw" onclick="togglePw('pw1')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
                <div class="pw-strength">
                    <div class="pw-bar" id="b1"></div>
                    <div class="pw-bar" id="b2"></div>
                    <div class="pw-bar" id="b3"></div>
                    <div class="pw-bar" id="b4"></div>
                </div>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <div class="input-wrap">
                    <input type="password" name="konfirmasi_password" id="pw2" placeholder="Ulangi password" required>
                    <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    <button type="button" class="toggle-pw" onclick="togglePw('pw2')">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </button>
                </div>
            </div>
        </div>
        <button type="submit" class="btn-primary">Daftar Sekarang</button>
    </form>
    <hr class="divider">
    <p class="footer-text">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
</div>
<script>
function togglePw(id) {
    const inp = document.getElementById(id);
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
function checkStrength(val) {
    const bars = [document.getElementById('b1'), document.getElementById('b2'), document.getElementById('b3'), document.getElementById('b4')];
    bars.forEach(b => b.className = 'pw-bar');
    if (!val) return;
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const cls = score <= 1 ? 'active-weak' : score <= 2 ? 'active-ok' : 'active-good';
    for (let i = 0; i < score; i++) bars[i].classList.add(cls);
}
</script>
</body>
</html>
