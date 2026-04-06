<?php
session_start();

// --- PASANG SATPAM DI SINI ---
// Jika belum login, tendang kembali ke login.php
if (!isset($_SESSION['sudah_login']) || $_SESSION['sudah_login'] !== true) {
    header("Location: login.php"); 
    exit;
}
// -----------------------------

require_once 'koneksi.php';

// MENGHITUNG TOTAL ANTRIAN HARI INI (Untuk Badge di Sidebar)
$antrian = 0;
try {
    // Asumsi status antrian bernama 'Antrian' atau 'antri'
    $stmt_antri = $pdo->query("SELECT COUNT(*) FROM pasien WHERE status_pasien = 'Antrian' AND DATE(created_at) = CURDATE()");
    $antrian = $stmt_antri->fetchColumn();
} catch (PDOException $e) {
    // Abaikan jika tabel atau kolom belum dibuat, set default 0
    $antrian = 0; 
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['simpan'])) {
    try {
        $sql = "INSERT INTO pasien (nik, nama_pasien, tanggal_lahir, jenis_kelamin, no_hp, alamat, keluhan_utama, diagnosa, tindakan) 
                VALUES (:nik, :nama, :tgl, :jk, :hp, :alamat, :keluhan, :diagnosa, :tindakan)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nik' => $_POST['nik'],
            ':nama' => $_POST['nama_pasien'],
            ':tgl' => $_POST['tanggal_lahir'],
            ':jk' => $_POST['jenis_kelamin'],
            ':hp' => $_POST['no_hp'],
            ':alamat' => $_POST['alamat'],
            ':keluhan' => $_POST['keluhan_utama'],
            ':diagnosa' => $_POST['diagnosa'],
            ':tindakan' => $_POST['tindakan']
        ]);
        
        // Buat notifikasi sukses dan arahkan otomatis ke dashboard
        $_SESSION['sukses'] = "Data pasien berhasil disimpan!";
        header("Location: index.php"); 
        exit;

    } catch (PDOException $e) {
        $error = "Gagal menyimpan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pasien — MedisDigital+</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --teal: #0a8f7f; --teal-mid: #0cb8a5; --teal-glow: rgba(10,143,127,0.18);
            --teal-light: rgba(10,143,127,0.08); --navy: #0d1f2d; --navy-mid: #142332;
            --navy-card: #162b3a; --border: rgba(255,255,255,0.07);
            --slate: #8da5b3; --text: #d0e4ef; --white: #ffffff;
            --sidebar-w: 240px; --success: #10b981; --danger: #ef4444;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--navy); color: var(--text); display: flex; min-height: 100vh; }

        /* Sidebar (same as dashboard) */
        .sidebar { width: var(--sidebar-w); background: var(--navy-card); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; z-index: 100; }
        .sidebar-logo { padding: 1.5rem 1.25rem; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, var(--teal), var(--teal-mid)); border-radius: 10px; display: flex; align-items: center; justify-content: center; }
        .logo-icon svg { width: 18px; height: 18px; color: white; }
        .logo-txt { font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1.05rem; color: var(--white); }
        .logo-txt span { color: var(--teal-mid); }
        .sidebar-nav { flex: 1; padding: 1.25rem 0.75rem; }
        .nav-section-title { font-size: 0.68rem; font-weight: 600; color: rgba(141,165,179,0.45); letter-spacing: 0.1em; text-transform: uppercase; padding: 0.5rem 0.6rem 0.35rem; margin-top: 0.75rem; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 0.6rem 0.75rem; border-radius: 10px; text-decoration: none; color: var(--slate); font-size: 0.875rem; font-weight: 500; transition: background 0.15s, color 0.15s; cursor: pointer; border: none; background: none; width: 100%; margin-bottom: 2px; }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: var(--white); }
        .nav-item.active { background: var(--teal-light); color: var(--teal-mid); }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-badge { margin-left: auto; background: #f59e0b; color: #0d1f2d; border-radius: 99px; padding: 1px 7px; font-size: 0.7rem; font-weight: 700; }
        .sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid var(--border); }
        .user-row { display: flex; align-items: center; gap: 10px; padding: 0.6rem 0.5rem; border-radius: 10px; }
        .avatar { width: 34px; height: 34px; border-radius: 50%; border: 2px solid var(--teal-glow); flex-shrink: 0; }
        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: 0.82rem; font-weight: 600; color: var(--white); }
        .user-role { font-size: 0.7rem; color: var(--slate); }
        .logout-btn { display: flex; align-items: center; gap: 8px; width: 100%; padding: 0.55rem 0.75rem; border-radius: 10px; color: #ef9999; background: none; border: none; cursor: pointer; font-size: 0.82rem; font-weight: 500; transition: background 0.15s; margin-top: 4px; text-decoration: none; }
        .logout-btn:hover { background: rgba(239,68,68,0.08); color: var(--danger); }
        .logout-btn svg { width: 16px; height: 16px; }

        /* Main */
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; }
        .topbar { background: rgba(13,31,45,0.8); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: 0 1.75rem; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .page-title { font-family: 'Sora', sans-serif; font-weight: 600; font-size: 0.95rem; color: var(--white); }
        .breadcrumb { font-size: 0.78rem; color: var(--slate); }
        .breadcrumb a { color: var(--teal-mid); text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }
        .icon-btn { width: 36px; height: 36px; border-radius: 9px; background: rgba(255,255,255,0.04); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--slate); }
        .icon-btn:hover { background: rgba(255,255,255,0.08); color: var(--white); }
        .icon-btn svg { width: 18px; height: 18px; }

        /* Content */
        .content { padding: 1.75rem; flex: 1; max-width: 860px; }
        
        .back-link { display: inline-flex; align-items: center; gap: 6px; color: var(--teal-mid); text-decoration: none; font-size: 0.85rem; font-weight: 500; margin-bottom: 1.25rem; transition: gap 0.2s; }
        .back-link:hover { gap: 10px; }
        .back-link svg { width: 16px; height: 16px; }

        .form-title { font-family: 'Sora', sans-serif; font-size: 1.3rem; font-weight: 700; color: var(--white); margin-bottom: 0.25rem; }
        .form-sub { font-size: 0.875rem; color: var(--slate); margin-bottom: 1.75rem; }

        /* Progress steps */
        .steps { display: flex; align-items: center; gap: 0; margin-bottom: 2rem; }
        .step { display: flex; align-items: center; gap: 8px; }
        .step-num { width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; flex-shrink: 0; }
        .step.active .step-num { background: linear-gradient(135deg, var(--teal), var(--teal-mid)); color: white; }
        .step.done .step-num { background: rgba(10,143,127,0.15); color: var(--teal-mid); border: 1px solid rgba(10,143,127,0.3); }
        .step.inactive .step-num { background: rgba(255,255,255,0.05); color: var(--slate); border: 1px solid var(--border); }
        .step-label { font-size: 0.78rem; font-weight: 500; }
        .step.active .step-label { color: var(--white); }
        .step.done .step-label { color: var(--teal-mid); }
        .step.inactive .step-label { color: var(--slate); }
        .step-line { flex: 1; height: 1px; background: var(--border); margin: 0 8px; }
        .step-line.done { background: rgba(10,143,127,0.3); }

        /* Card sections */
        .form-card { background: var(--navy-card); border: 1px solid var(--border); border-radius: 16px; padding: 1.5rem; margin-bottom: 1.25rem; }
        .form-card-title { font-family: 'Sora', sans-serif; font-size: 0.9rem; font-weight: 600; color: var(--white); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 8px; }
        .form-card-title svg { width: 18px; height: 18px; color: var(--teal-mid); }
        .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
        .col-span-2 { grid-column: span 2; }
        .form-group { display: flex; flex-direction: column; gap: 0.4rem; }
        label { font-size: 0.78rem; font-weight: 500; color: #9ab5c4; letter-spacing: 0.04em; text-transform: uppercase; }
        .req { color: #f87171; margin-left: 2px; }
        .input-wrap { position: relative; }
        .input-wrap svg.ico { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; color: var(--slate); pointer-events: none; }
        input, select, textarea { width: 100%; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.09); border-radius: 10px; padding: 0.7rem 1rem 0.7rem 2.5rem; font-family: 'DM Sans', sans-serif; font-size: 0.875rem; color: var(--text); outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s; appearance: none; }
        input:focus, select:focus, textarea:focus { border-color: var(--teal); background: rgba(10,143,127,0.05); box-shadow: 0 0 0 3px var(--teal-glow); }
        input::placeholder, textarea::placeholder { color: rgba(141,165,179,0.4); }
        textarea { padding-top: 0.75rem; resize: vertical; min-height: 90px; }
        select { padding-right: 2rem; }
        .select-wrap { position: relative; }
        .select-wrap::after { content: ''; position: absolute; right: 13px; top: 50%; transform: translateY(-50%); width: 0; height: 0; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 4px solid var(--slate); pointer-events: none; }
        .select-wrap svg.ico { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; color: var(--slate); pointer-events: none; }
        select option { background: var(--navy-mid); }

        /* Vitals grid */
        .vitals-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
        .vital-item { background: rgba(255,255,255,0.02); border: 1px solid var(--border); border-radius: 10px; padding: 0.75rem; }
        .vital-item label { display: block; margin-bottom: 0.4rem; }
        .vital-item input { padding-left: 1rem; }

        /* Textarea no icon */
        .no-icon textarea { padding-left: 1rem; }

        /* Bottom actions */
        .form-actions { display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem; gap: 0.75rem; }
        .btn-ghost { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,0.05); border: 1px solid var(--border); border-radius: 10px; padding: 0.7rem 1.25rem; font-size: 0.875rem; color: var(--text); cursor: pointer; font-family: 'DM Sans', sans-serif; transition: background 0.15s; text-decoration: none; }
        .btn-ghost:hover { background: rgba(255,255,255,0.08); }
        .btn-ghost svg { width: 16px; height: 16px; }
        .btn-primary { display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, var(--teal), var(--teal-mid)); color: white; border: none; border-radius: 10px; padding: 0.7rem 1.5rem; font-family: 'Sora', sans-serif; font-size: 0.9rem; font-weight: 600; cursor: pointer; box-shadow: 0 6px 18px rgba(10,143,127,0.3); transition: transform 0.15s, box-shadow 0.15s; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(10,143,127,0.4); }
        .btn-primary svg { width: 16px; height: 16px; }

        /* Toast */
        .toast { position: fixed; bottom: 24px; right: 24px; background: var(--navy-card); border: 1px solid rgba(16,185,129,0.3); border-radius: 12px; padding: 0.9rem 1.25rem; display: flex; align-items: center; gap: 10px; font-size: 0.875rem; color: var(--text); box-shadow: 0 16px 40px rgba(0,0,0,0.3); z-index: 300; transform: translateY(100px); opacity: 0; transition: transform 0.3s, opacity 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast-icon { width: 20px; height: 20px; color: #34d399; flex-shrink: 0; }

        @media (max-width: 900px) {
            .sidebar { display: none; }
            .main { margin-left: 0; }
            .grid-2, .vitals-grid { grid-template-columns: 1fr; }
            .col-span-2 { grid-column: span 1; }
        }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div class="logo-text">Medis<span>Digital+</span></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-title">Menu Utama</div>
        <a href="index.php" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <button class="nav-item active">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Data Pasien
        </button>
        <button class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Antrian
            <span class="nav-badge"><?php echo $antrian; ?></span>
        </button>
        <button class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Rekam Medis
        </button>
    </nav>
    <div class="sidebar-footer">
        <div class="user-row">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama'] ?? 'Admin'); ?>&background=0a8f7f&color=fff" class="avatar" alt="Avatar">
            <div class="user-info">
                <div class="user-name"><?php echo htmlspecialchars($_SESSION['nama'] ?? 'Administrator'); ?></div>
                <div class="user-role" style="text-transform: capitalize;"><?php echo htmlspecialchars($_SESSION['peran'] ?? 'Klinik Staff'); ?></div>
            </div>
        </div>
        <a href="logout.php" class="logout-btn">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
        </a>
    </div>
</aside>

<div class="main">
    <header class="topbar">
        <div class="topbar-left">
            <div>
                <div class="page-title">Tambah Pasien</div>
                <div class="breadcrumb"><a href="index.php">Dashboard</a> / <span style="color:var(--text)">Form Rekam Medis</span></div>
            </div>
        </div>
        <div style="display:flex;gap:0.75rem;align-items:center;">
            <div class="icon-btn">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama'] ?? 'Admin'); ?>&background=0a8f7f&color=fff" class="avatar" alt="Avatar">
        </div>
    </header>

    <main class="content">
        <a href="index.php" class="back-link">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
        <div class="form-title">Form Rekam Medis Baru</div>
        <div class="form-sub">Lengkapi semua data pasien dengan benar dan teliti</div>

        <div class="steps">
            <div class="step active">
                <div class="step-num">1</div>
                <div class="step-label">Data Pribadi</div>
            </div>
            <div class="step-line"></div>
            <div class="step inactive">
                <div class="step-num">2</div>
                <div class="step-label">Pemeriksaan</div>
            </div>
            <div class="step-line"></div>
            <div class="step inactive">
                <div class="step-num">3</div>
                <div class="step-label">Resep & Tindakan</div>
            </div>
        </div>

        <?php if (isset($error)): ?>
            <div style="background: var(--danger); color: white; padding: 10px 15px; border-radius: 8px; margin-bottom: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form id="patientForm" method="POST" action="">
            
            <input type="hidden" name="tindakan" value="-">

            <div class="form-card">
                <div class="form-card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Identitas Pasien
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Nama Lengkap <span class="req">*</span></label>
                        <div class="input-wrap">
                            <input type="text" name="nama_pasien" placeholder="Nama lengkap pasien" required>
                            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>NIK / No. KTP</label>
                        <div class="input-wrap">
                            <input type="text" name="nik" placeholder="16 digit NIK" maxlength="16">
                            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="req">*</span></label>
                        <div class="input-wrap">
                            <input type="date" name="tanggal_lahir" required style="color-scheme:dark;">
                            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="req">*</span></label>
                        <div class="select-wrap">
                            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            <select name="jenis_kelamin" required>
                                <option value="">-- Pilih --</option>
                                <option value="Laki-laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>No. HP / Telepon</label>
                        <div class="input-wrap">
                            <input type="tel" name="no_hp" placeholder="0812xxxxxxxx">
                            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Golongan Darah</label>
                        <div class="select-wrap">
                            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            <select name="golongan_darah">
                                <option value="">-- Pilih --</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-span-2">
                        <label>Alamat Lengkap</label>
                        <div class="no-icon">
                            <textarea name="alamat" placeholder="Jl. ... No. ..., Kelurahan, Kecamatan, Kota"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    Tanda-Tanda Vital
                </div>
                <div class="vitals-grid">
                    <div class="vital-item form-group">
                        <label>Suhu (°C)</label>
                        <input type="number" name="suhu" step="0.1" placeholder="36.5" style="padding-left:1rem;">
                    </div>
                    <div class="vital-item form-group">
                        <label>Tekanan Darah</label>
                        <input type="text" name="tekanan_darah" placeholder="120/80" style="padding-left:1rem;">
                    </div>
                    <div class="vital-item form-group">
                        <label>Berat Badan (kg)</label>
                        <input type="number" name="berat_badan" step="0.1" placeholder="65" style="padding-left:1rem;">
                    </div>
                    <div class="vital-item form-group">
                        <label>Tinggi Badan (cm)</label>
                        <input type="number" name="tinggi_badan" placeholder="170" style="padding-left:1rem;">
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-card-title">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    Keluhan & Diagnosa
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Status Pasien <span class="req">*</span></label>
                        <div class="select-wrap">
                            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <select name="status_pasien" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="Antrian">Antrian</option>
                                <option value="Rawat Jalan">Rawat Jalan</option>
                                <option value="Rawat Inap">Rawat Inap</option>
                                <option value="Darurat">Darurat</option>
                                <option value="Selesai">Selesai</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Dokter Pemeriksa</label>
                        <div class="select-wrap">
                            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <select name="dokter_pemeriksa">
                                <option value="dr. Dimas">dr. Dimas</option>
                                <option value="dr. Anisa Putri">dr. Anisa Putri</option>
                                <option value="dr. Budi Hartono">dr. Budi Hartono</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-span-2">
                        <label>Keluhan Utama <span class="req">*</span></label>
                        <div class="no-icon">
                            <textarea name="keluhan_utama" placeholder="Jelaskan keluhan utama yang dirasakan pasien..." required></textarea>
                        </div>
                    </div>
                    <div class="form-group col-span-2">
                        <label>Diagnosa Dokter</label>
                        <div class="no-icon">
                            <textarea name="diagnosa" placeholder="Hasil diagnosa dan catatan medis dokter..."></textarea>
                        </div>
                    </div>
                    <div class="form-group col-span-2">
                        <label>Riwayat Alergi</label>
                        <div class="input-wrap">
                            <input type="text" name="riwayat_alergi" placeholder="Contoh: Penisilin, Ibuprofen, Tidak ada">
                            <svg class="ico" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn-ghost">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Batal
                </a>
                <div style="display:flex;gap:0.75rem;">
                    <button type="button" class="btn-ghost">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                        Simpan Draft
                    </button>
                    <button type="submit" name="simpan" class="btn-primary">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Simpan & Lanjutkan
                    </button>
                </div>
            </div>
        </form>
    </main>
</div>

</body>
</html>
