<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['sudah_login']) || $_SESSION['sudah_login'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'koneksi.php';

// Mengambil data dan menghitung statistik
try {
    // 1. Mengambil semua data pasien untuk ditabel
    $stmt = $pdo->query("SELECT * FROM pasien ORDER BY created_at DESC");
    $data_pasien = $stmt->fetchAll();
    
    // 2. Total Semua Pasien
    $total_pasien = count($data_pasien);

    // 3. Total Pemeriksaan Hari Ini
    $stmt_today = $pdo->query("SELECT COUNT(*) FROM pasien WHERE DATE(created_at) = CURDATE()");
    $hari_ini = $stmt_today->fetchColumn();

    // 4. Menunggu Antrian (Asumsi ada kolom status = 'antri', jika tidak ada kita set default 0)
    $antrian = 0;
    try {
        $stmt_antri = $pdo->query("SELECT COUNT(*) FROM pasien WHERE status = 'antri' AND DATE(created_at) = CURDATE()");
        $antrian = $stmt_antri->fetchColumn();
    } catch (PDOException $e) {
        // Abaikan error jika kolom status belum dibuat di database
        $antrian = 0; 
    }

} catch (PDOException $e) {
    die("Gagal mengambil data: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — MedisDigital+</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&display=swap" rel="stylesheet">
    <style>
        /* SAMA SEPERTI SEBELUMNYA */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --teal: #0a8f7f; --teal-dark: #076d60; --teal-mid: #0cb8a5; --teal-light: rgba(10,143,127,0.1);
            --teal-glow: rgba(10,143,127,0.2); --navy: #0d1f2d; --navy-mid: #142332;
            --navy-card: #162b3a; --navy-hover: #1a3347; --border: rgba(255,255,255,0.07);
            --slate: #8da5b3; --text: #d0e4ef; --white: #ffffff;
            --sidebar-w: 240px;
            --warn: #f59e0b; --success: #10b981; --info: #3b82f6; --danger: #ef4444;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--navy); color: var(--text); display: flex; min-height: 100vh; }

        .sidebar { width: var(--sidebar-w); background: var(--navy-card); border-right: 1px solid var(--border); display: flex; flex-direction: column; position: fixed; top: 0; left: 0; height: 100vh; z-index: 100; transition: transform 0.3s; flex-shrink: 0; }
        .sidebar-logo { padding: 1.5rem 1.25rem; display: flex; align-items: center; gap: 10px; border-bottom: 1px solid var(--border); }
        .logo-icon { width: 36px; height: 36px; background: linear-gradient(135deg, var(--teal), var(--teal-mid)); border-radius: 10px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 16px var(--teal-glow); flex-shrink: 0; }
        .logo-icon svg { width: 18px; height: 18px; color: white; }
        .logo-txt { font-family: 'Sora', sans-serif; font-weight: 700; font-size: 1.05rem; color: var(--white); }
        .logo-txt span { color: var(--teal-mid); }
        .sidebar-nav { flex: 1; padding: 1.25rem 0.75rem; overflow-y: auto; }
        .nav-section-title { font-size: 0.68rem; font-weight: 600; color: rgba(141,165,179,0.45); letter-spacing: 0.1em; text-transform: uppercase; padding: 0.5rem 0.6rem 0.35rem; margin-top: 0.75rem; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 0.6rem 0.75rem; border-radius: 10px; text-decoration: none; color: var(--slate); font-size: 0.875rem; font-weight: 500; transition: background 0.15s, color 0.15s; cursor: pointer; border: none; background: none; width: 100%; margin-bottom: 2px; }
        .nav-item:hover { background: rgba(255,255,255,0.05); color: var(--white); }
        .nav-item.active { background: var(--teal-light); color: var(--teal-mid); }
        .nav-item.active svg { color: var(--teal-mid); }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }
        .nav-badge { margin-left: auto; background: var(--warn); color: #0d1f2d; border-radius: 99px; padding: 1px 7px; font-size: 0.7rem; font-weight: 700; }
        .sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid var(--border); }
        .user-row { display: flex; align-items: center; gap: 10px; padding: 0.6rem 0.5rem; border-radius: 10px; cursor: pointer; }
        .user-row:hover { background: rgba(255,255,255,0.04); }
        .avatar { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; border: 2px solid var(--teal-glow); flex-shrink: 0; }
        .user-info { flex: 1; min-width: 0; }
        .user-name { font-size: 0.82rem; font-weight: 600; color: var(--white); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-role { font-size: 0.7rem; color: var(--slate); }
        .logout-btn { display: flex; align-items: center; gap: 8px; width: 100%; padding: 0.55rem 0.75rem; border-radius: 10px; color: #ef9999; background: none; border: none; cursor: pointer; font-size: 0.82rem; font-weight: 500; transition: background 0.15s; margin-top: 4px; text-decoration: none; }
        .logout-btn:hover { background: rgba(239,68,68,0.08); color: var(--danger); }
        .logout-btn svg { width: 16px; height: 16px; }
        .main { margin-left: var(--sidebar-w); flex: 1; display: flex; flex-direction: column; min-height: 100vh; }
        .topbar { background: rgba(13,31,45,0.8); backdrop-filter: blur(12px); border-bottom: 1px solid var(--border); padding: 0 1.75rem; height: 60px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 50; }
        .topbar-left { display: flex; align-items: center; gap: 12px; }
        .page-title { font-family: 'Sora', sans-serif; font-weight: 600; font-size: 0.95rem; color: var(--white); }
        .breadcrumb { font-size: 0.78rem; color: var(--slate); }
        .breadcrumb span { color: var(--teal-mid); }
        .topbar-right { display: flex; align-items: center; gap: 0.75rem; }
        .icon-btn { width: 36px; height: 36px; border-radius: 9px; background: rgba(255,255,255,0.04); border: 1px solid var(--border); display: flex; align-items: center; justify-content: center; cursor: pointer; color: var(--slate); transition: background 0.15s, color 0.15s; }
        .icon-btn:hover { background: rgba(255,255,255,0.08); color: var(--white); }
        .icon-btn svg { width: 18px; height: 18px; }
        .notif-wrap { position: relative; }
        .notif-dot { position: absolute; top: 6px; right: 6px; width: 8px; height: 8px; background: var(--danger); border-radius: 50%; border: 2px solid var(--navy); }
        .content { padding: 1.75rem; flex: 1; }
        .hero { background: linear-gradient(135deg, var(--navy-card) 0%, #0d2c3f 100%); border: 1px solid var(--border); border-radius: 20px; padding: 2rem 2.5rem; margin-bottom: 1.75rem; display: flex; justify-content: space-between; align-items: center; overflow: hidden; position: relative; }
        .hero::before { content: ''; position: absolute; right: -80px; top: -80px; width: 280px; height: 280px; background: radial-gradient(circle, rgba(10,143,127,0.18) 0%, transparent 65%); pointer-events: none; }
        .hero::after { content: ''; position: absolute; left: 40%; bottom: -60px; width: 200px; height: 200px; background: radial-gradient(circle, rgba(10,143,127,0.08) 0%, transparent 65%); pointer-events: none; }
        .hero-text h1 { font-family: 'Sora', sans-serif; font-size: 1.6rem; font-weight: 700; color: var(--white); margin-bottom: 0.4rem; }
        .hero-text p { font-size: 0.9rem; color: var(--slate); max-width: 420px; line-height: 1.6; }
        .hero-date { background: rgba(10,143,127,0.12); border: 1px solid rgba(10,143,127,0.2); border-radius: 12px; padding: 0.6rem 1.2rem; font-size: 0.8rem; font-weight: 500; color: var(--teal-mid); white-space: nowrap; z-index: 1; }
        .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1.75rem; }
        .stat-card { background: var(--navy-card); border: 1px solid var(--border); border-radius: 16px; padding: 1.4rem 1.5rem; display: flex; align-items: center; gap: 1rem; transition: border-color 0.2s, transform 0.2s; }
        .stat-card:hover { border-color: rgba(10,143,127,0.3); transform: translateY(-2px); }
        .stat-icon { width: 48px; height: 48px; border-radius: 13px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
        .stat-icon svg { width: 22px; height: 22px; }
        .stat-icon.blue { background: rgba(59,130,246,0.12); color: #60a5fa; }
        .stat-icon.teal { background: rgba(10,143,127,0.12); color: var(--teal-mid); }
        .stat-icon.amber { background: rgba(245,158,11,0.12); color: #fbbf24; }
        .stat-label { font-size: 0.78rem; color: var(--slate); margin-bottom: 0.25rem; }
        .stat-value { font-family: 'Sora', sans-serif; font-size: 1.6rem; font-weight: 700; color: var(--white); line-height: 1; }
        .stat-sub { font-size: 0.72rem; color: var(--teal-mid); margin-top: 4px; }
        .section-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .section-title { font-family: 'Sora', sans-serif; font-size: 1rem; font-weight: 600; color: var(--white); }
        .section-sub { font-size: 0.78rem; color: var(--slate); margin-top: 2px; }
        .btn-primary { display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, var(--teal), var(--teal-mid)); color: white; border: none; border-radius: 10px; padding: 0.55rem 1.1rem; font-family: 'DM Sans', sans-serif; font-size: 0.85rem; font-weight: 600; cursor: pointer; text-decoration: none; box-shadow: 0 4px 14px rgba(10,143,127,0.3); transition: transform 0.15s, box-shadow 0.15s; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(10,143,127,0.4); }
        .btn-primary svg { width: 16px; height: 16px; }
        .search-filter { display: flex; gap: 0.75rem; margin-bottom: 1rem; }
        .search-wrap { flex: 1; position: relative; }
        .search-wrap svg { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; height: 16px; color: var(--slate); }
        .search-input { width: 100%; background: var(--navy-card); border: 1px solid var(--border); border-radius: 10px; padding: 0.6rem 1rem 0.6rem 2.4rem; font-family: 'DM Sans', sans-serif; font-size: 0.85rem; color: var(--text); outline: none; transition: border-color 0.2s; }
        .search-input:focus { border-color: var(--teal); }
        .search-input::placeholder { color: rgba(141,165,179,0.4); }
        .filter-btn { display: flex; align-items: center; gap: 6px; background: var(--navy-card); border: 1px solid var(--border); border-radius: 10px; padding: 0 1rem; font-size: 0.82rem; color: var(--slate); cursor: pointer; transition: border-color 0.15s; }
        .filter-btn:hover { border-color: rgba(255,255,255,0.2); color: var(--white); }
        .filter-btn svg { width: 15px; height: 15px; }
        .table-wrap { background: var(--navy-card); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border); }
        th { padding: 0.9rem 1.1rem; text-align: left; font-size: 0.73rem; font-weight: 600; color: rgba(141,165,179,0.7); letter-spacing: 0.06em; text-transform: uppercase; white-space: nowrap; }
        tbody tr { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: rgba(255,255,255,0.025); }
        td { padding: 0.9rem 1.1rem; font-size: 0.875rem; color: var(--text); vertical-align: middle; }
        .badge { display: inline-flex; align-items: center; gap: 5px; padding: 0.3rem 0.65rem; border-radius: 99px; font-size: 0.7rem; font-weight: 700; white-space: nowrap; }
        .badge-dot { width: 6px; height: 6px; border-radius: 50%; }
        .badge-selesai { background: rgba(16,185,129,0.1); color: #34d399; border: 1px solid rgba(16,185,129,0.2); }
        .action-btns { display: flex; justify-content: flex-end; gap: 4px; }
        .act-btn { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: none; cursor: pointer; transition: background 0.15s; }
        .act-btn svg { width: 15px; height: 15px; }
        .act-edit { background: rgba(59,130,246,0.1); color: #60a5fa; }
        .act-edit:hover { background: rgba(59,130,246,0.2); }
        .act-del { background: rgba(239,68,68,0.08); color: #f87171; }
        .act-del:hover { background: rgba(239,68,68,0.15); }
    </style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
        </div>
        <div class="logo-text">Medis<span>Digital+</span></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-title">Menu Utama</div>
        <button class="nav-item active">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </button>
        <a href="form-pasien.php" class="nav-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Data Pasien
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-row">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama'] ?? 'Admin'); ?>&background=0a8f7f&color=fff" class="avatar" alt="Avatar">
         <div class="user-info">
                <div class="user-name"><?php echo $_SESSION['nama'] ?? 'Administrator'; ?></div>
                <div class="user-role" style="text-transform: capitalize;"><?php echo $_SESSION['peran'] ?? 'Klinik Staff'; ?></div>
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
                <div class="page-title">Dashboard</div>
                <div class="breadcrumb">MedisDigital+ / <span>Beranda</span></div>
            </div>
        </div>
        <div class="topbar-right">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['nama'] ?? 'Admin'); ?>&background=0a8f7f&color=fff" class="avatar" alt="Avatar" style="cursor:pointer;">
        </div>
    </header>

    <main class="content">
        <div class="hero">
            <div class="hero-text">
                <h1>Selamat Datang, <?php echo $_SESSION['nama'] ?? 'Admin'; ?></h1>
                <p>Pantau riwayat kesehatan pasien dan kelola antrian klinik dengan lebih cepat dan terpadu.</p>
            </div>
            <div class="hero-date">📅 <?php echo date('l, d F Y'); ?></div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <div>
                    <div class="stat-label">Total Pasien Terdaftar</div>
                    <div class="stat-value"><?php echo $total_pasien; ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon teal">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <div>
                    <div class="stat-label">Pemeriksaan Hari Ini</div>
                    <div class="stat-value"><?php echo $hari_ini; ?></div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon amber">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <div class="stat-label">Menunggu Antrian</div>
                    <div class="stat-value"><?php echo $antrian; ?></div>
                </div>
            </div>
        </div>

        <div class="section-head">
            <div>
                <div class="section-title">Data Rekam Medis</div>
                <div class="section-sub">Daftar riwayat pemeriksaan terbaru</div>
            </div>
            <a href="form-pasien.php" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                Tambah Pasien
            </a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Pasien</th>
                        <th>Tgl. Periksa</th>
                        <th>Keluhan / Diagnosa</th>
                        <th>Status</th>
                        <th style="text-align:right">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php if (count($data_pasien) > 0): ?>
                        <?php foreach ($data_pasien as $row): ?>
                            <tr>
                                <td>
                                    <div style="font-weight:600; color:var(--white);"><?php echo htmlspecialchars($row['nama_pasien']); ?></div>
                                    <div style="font-size:0.75rem; color:var(--slate); margin-top:2px;">NIK: <?php echo htmlspecialchars($row['nik']); ?></div>
                                </td>
                                <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                <td>
                                    <div style="color:var(--text); font-size:0.85rem; font-weight:500;"><?php echo htmlspecialchars($row['keluhan_utama']); ?></div>
                                    <div style="font-size:0.75rem; color:var(--slate); margin-top:2px;"><?php echo htmlspecialchars($row['diagnosa']); ?></div>
                                </td>
                                <td>
                                    <span class="badge badge-selesai"><span class="badge-dot"></span>Terdata</span>
                                </td>
                                <td>
                                    <div class="action-btns">
                                        <button class="act-btn act-edit" title="Edit"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                        <button class="act-btn act-del" title="Hapus"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 30px; color: var(--slate);">Belum ada data pasien terdaftar.</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>

