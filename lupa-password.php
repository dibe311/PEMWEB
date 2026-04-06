<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — MedisDigital+</title>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --teal: #0a8f7f; --teal-dark: #076d60; --teal-light: #e0f5f2;
            --teal-glow: rgba(10,143,127,0.18); --navy: #0d1f2d; --navy-mid: #162b3a;
            --slate: #8da5b3; --white: #ffffff;
        }
        body { font-family: 'DM Sans', sans-serif; background-color: var(--navy); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 1.5rem; overflow: hidden; }
        .bg-decor { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
        .bg-decor::before { content: ''; position: absolute; width: 500px; height: 500px; background: radial-gradient(circle, rgba(10,143,127,0.15) 0%, transparent 70%); top: -100px; right: -100px; animation: pulse-slow 8s ease-in-out infinite; }
        @keyframes pulse-slow { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.15); } }
        .grid-dots { position: fixed; inset: 0; z-index: 0; background-image: radial-gradient(circle, rgba(255,255,255,0.04) 1px, transparent 1px); background-size: 32px 32px; }
        .card { position: relative; z-index: 10; background: rgba(22, 43, 58, 0.88); backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.07); border-radius: 24px; padding: 2.5rem; width: 100%; max-width: 420px; box-shadow: 0 40px 80px rgba(0,0,0,0.4); animation: card-in 0.6s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes card-in { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        .logo-wrap { display: flex; align-items: center; gap: 10px; margin-bottom: 2rem; }
        .logo-icon { width: 40px; height: 40px; background: linear-gradient(135deg, var(--teal), #0cc4ae); border-radius: 12px; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px var(--teal-glow); }
        .logo-icon svg { width: 22px; height: 22px; color: white; }
        .logo-text { font-family: 'Sora', sans-serif; font-size: 1.25rem; font-weight: 700; color: var(--white); }
        .logo-text span { color: #0cc4ae; }
        .icon-circle { width: 56px; height: 56px; background: rgba(10,143,127,0.12); border: 1px solid rgba(10,143,127,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.25rem; }
        .icon-circle svg { width: 26px; height: 26px; color: #0cc4ae; }
        h2 { font-family: 'Sora', sans-serif; font-size: 1.4rem; font-weight: 600; color: var(--white); margin-bottom: 0.4rem; }
        .subtitle { font-size: 0.875rem; color: var(--slate); margin-bottom: 1.75rem; line-height: 1.6; }
        .form-group { margin-bottom: 1.2rem; }
        label { display: block; font-size: 0.8rem; font-weight: 500; color: #9ab5c4; margin-bottom: 0.5rem; letter-spacing: 0.04em; text-transform: uppercase; }
        .input-wrap { position: relative; }
        .input-wrap svg.icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--slate); pointer-events: none; }
        input[type=email], input[type=text] { width: 100%; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 0.75rem 1rem 0.75rem 2.75rem; font-family: 'DM Sans', sans-serif; font-size: 0.9rem; color: var(--white); outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s; }
        input:focus { border-color: var(--teal); background: rgba(10,143,127,0.06); box-shadow: 0 0 0 3px var(--teal-glow); }
        input::placeholder { color: rgba(141,165,179,0.5); }
        .btn-primary { width: 100%; background: linear-gradient(135deg, var(--teal), #0cc4ae); color: white; border: none; border-radius: 12px; padding: 0.875rem; font-family: 'Sora', sans-serif; font-size: 0.95rem; font-weight: 600; cursor: pointer; box-shadow: 0 8px 24px rgba(10,143,127,0.35); transition: transform 0.15s, box-shadow 0.15s; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 12px 32px rgba(10,143,127,0.45); }
        .divider { border: none; border-top: 1px solid rgba(255,255,255,0.07); margin: 1.5rem 0; }
        .footer-text { text-align: center; font-size: 0.85rem; color: var(--slate); }
        .footer-text a { color: #0cc4ae; font-weight: 500; text-decoration: none; }
        .footer-text a:hover { text-decoration: underline; }
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
    <div class="icon-circle">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
    </div>
    <h2>Reset Password</h2>
    <p class="subtitle">Masukkan email Anda dan kami akan mengirimkan tautan untuk mereset password.</p>
    <form action="login.html">
        <div class="form-group">
            <label>Email Terdaftar</label>
            <div class="input-wrap">
                <input type="email" placeholder="email@klinik.id" required>
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
        </div>
        <button type="submit" class="btn-primary">Kirim Link Reset</button>
    </form>
    <hr class="divider">
    <p class="footer-text"><a href="login.html">← Kembali ke halaman login</a></p>
</div>
</body>
</html>
