<?php
/**
 * initial_checks/index.php — MODULE 3
 * FIX: Tampilkan semua antrian yang perlu pemeriksaan awal:
 *   - Pending: status='waiting' dan belum ada initial_check
 *   - Juga antrian status='called'/'in_progress' yang sudah dicek (readonly info)
 *   - Kolom "Keluhan" di tabel "Sudah Diperiksa" supaya perawat bisa lihat ringkasan
 */
require_once __DIR__ . '/../config/app.php';
require_once __DIR__ . '/../config/database.php';
requireRole(['admin','perawat']);

$db    = getDB();
$today = date('Y-m-d');
$date  = $_GET['date'] ?? $today;

// FIX: Antrian yang BELUM diperiksa (status waiting, belum ada IC)
$pending = $db->prepare("
    SELECT q.id, q.queue_number, q.status, q.created_at,
           p.name AS patient_name, p.birth_date, p.gender, p.allergy,
           u.name AS doctor_name
    FROM queues q
    LEFT JOIN patients p        ON q.patient_id = p.id
    LEFT JOIN users u           ON q.doctor_id  = u.id
    LEFT JOIN initial_checks ic ON ic.queue_id  = q.id
    WHERE q.queue_date = ? AND q.status = 'waiting' AND ic.id IS NULL
    ORDER BY q.created_at ASC
");
$pending->execute([$date]);
$pending = $pending->fetchAll();

// FIX: Sudah diperiksa hari ini — tampilkan vital + keluhan utama
$checked = $db->prepare("
    SELECT q.id, q.queue_number, q.status, ic.checked_at,
           p.name AS patient_name,
           ic.blood_pressure, ic.temperature, ic.pulse, ic.oxygen_saturation,
           ic.chief_complaint,
           u.name AS nurse_name
    FROM initial_checks ic
    LEFT JOIN queues q   ON q.id    = ic.queue_id
    LEFT JOIN patients p ON p.id    = ic.patient_id
    LEFT JOIN users u    ON u.id    = ic.nurse_id
    WHERE q.queue_date = ?
    ORDER BY ic.checked_at DESC
");
$checked->execute([$date]);
$checked = $checked->fetchAll();

// FIX: Hitung antrian yang sudah called/in_progress (untuk info header)
$activeCount = $db->prepare("SELECT COUNT(*) FROM queues WHERE queue_date=? AND status IN ('called','in_progress')");
$activeCount->execute([$date]);
$activeCount = (int)$activeCount->fetchColumn();

$pageTitle  = 'Pemeriksaan Awal';
$activeMenu = 'initial_checks';
ob_start();
?>
<div class="page-header">
  <div>
    <h1 class="page-title">Pemeriksaan Awal</h1>
    <p class="page-subtitle">Triase &amp; input tanda vital pasien sebelum ke dokter</p>
  </div>
  <div style="display:flex;gap:8px;align-items:center">
    <form method="GET" style="display:flex;gap:8px;align-items:center">
      <input class="form-control" type="date" name="date"
             value="<?= sanitize($date) ?>" style="width:auto">
      <button class="btn btn-outline">Filter</button>
    </form>
    <a href="<?= BASE_URL ?>/queues/index.php" class="btn btn-ghost">← Antrian</a>
  </div>
</div>

<!-- FIX: Info bar ringkasan status hari ini -->
<div style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
  <div style="background:var(--amber-bg);border:1px solid var(--amber-border);border-radius:var(--r);
              padding:8px 14px;font-size:13px;font-weight:600;color:var(--amber)">
    ⏳ <?= count($pending) ?> menunggu pemeriksaan
  </div>
  <div style="background:var(--green-bg);border:1px solid var(--green-border);border-radius:var(--r);
              padding:8px 14px;font-size:13px;font-weight:600;color:var(--green)">
    ✓ <?= count($checked) ?> sudah diperiksa
  </div>
  <?php if ($activeCount > 0): ?>
  <div style="background:var(--blue-pale);border:1px solid var(--blue-muted);border-radius:var(--r);
              padding:8px 14px;font-size:13px;font-weight:600;color:var(--blue)">
    🩺 <?= $activeCount ?> sedang/sudah ke dokter
  </div>
  <?php endif; ?>
</div>

<div class="two-col-grid">

  <!-- ── Menunggu Pemeriksaan ── -->
  <div class="card">
    <div class="card-header">
      <span class="card-title">Menunggu Pemeriksaan</span>
      <span class="badge badge-waiting"><?= count($pending) ?> pasien</span>
    </div>
    <?php if ($pending): ?>
    <div style="padding:8px">
      <?php foreach ($pending as $q): ?>
      <div class="call-card">
        <div class="call-num"><?= sanitize($q['queue_number']) ?></div>
        <div class="call-info" style="flex:1">
          <div class="call-patient"><?= sanitize($q['patient_name']) ?></div>
          <div class="call-sub">
            <?= $q['gender']==='L' ? 'Laki-laki' : 'Perempuan' ?> ·
            <?= calculateAge($q['birth_date']) ?> tahun
            <?php if ($q['allergy']): ?>
              · <span class="allergy-badge">⚠ Alergi</span>
            <?php endif; ?>
          </div>
          <?php if ($q['doctor_name']): ?>
          <div class="text-xs text-muted mt-1">→ dr. <?= sanitize($q['doctor_name']) ?></div>
          <?php endif; ?>
          <div class="text-xs text-muted"><?= date('H:i', strtotime($q['created_at'])) ?> WIB</div>
        </div>
        <a href="<?= BASE_URL ?>/initial_checks/create.php?queue_id=<?= $q['id'] ?>"
           class="btn btn-primary btn-sm">
          <svg viewBox="0 0 20 20" fill="currentColor" style="width:14px;height:14px"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
          Periksa
        </a>
      </div>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="empty-state" style="padding:28px">
      <div class="empty-state-icon">
        <svg viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
      </div>
      <p class="empty-state-title">Semua pasien sudah diperiksa 🎉</p>
      <?php if ($date === $today): ?>
      <p class="text-sm text-muted">Antrian baru akan muncul otomatis di sini</p>
      <?php endif; ?>
    </div>
    <?php endif; ?>
  </div>

  <!-- ── Sudah Diperiksa ── -->
  <div class="card">
    <div class="card-header">
      <span class="card-title">Sudah Diperiksa</span>
      <span class="badge badge-done"><?= count($checked) ?> pasien</span>
    </div>
    <?php if ($checked): ?>
    <div class="table-wrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>No.</th>
            <th>Pasien</th>
            <th>Vital</th>
            <th>Keluhan</th>
            <th>Perawat</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($checked as $c): ?>
          <tr>
            <td><strong style="color:var(--blue)"><?= sanitize($c['queue_number']) ?></strong></td>
            <td class="td-name"><?= sanitize($c['patient_name']) ?></td>
            <td style="font-size:11px;white-space:nowrap">
              <?php if ($c['blood_pressure']): ?>
              <div>🩺 <?= sanitize($c['blood_pressure']) ?> mmHg</div>
              <?php endif; ?>
              <?php if ($c['temperature']): ?>
              <div>🌡 <?= number_format($c['temperature'],1) ?>°C</div>
              <?php endif; ?>
              <?php if ($c['pulse']): ?>
              <div>💓 <?= (int)$c['pulse'] ?> bpm</div>
              <?php endif; ?>
              <?php if ($c['oxygen_saturation']): ?>
              <div>🩸 <?= (int)$c['oxygen_saturation'] ?>%</div>
              <?php endif; ?>
              <?php if (!$c['blood_pressure'] && !$c['temperature']): ?>
              <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td style="font-size:12px;max-width:140px">
              <?php if ($c['chief_complaint']): ?>
              <span title="<?= sanitize($c['chief_complaint']) ?>"
                    style="display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                <?= sanitize(mb_substr($c['chief_complaint'],0,50)) ?>
              </span>
              <?php else: ?>
              <span class="text-muted">—</span>
              <?php endif; ?>
            </td>
            <td class="text-xs text-muted">
              <?= sanitize($c['nurse_name']) ?>
              <div><?= date('H:i', strtotime($c['checked_at'])) ?></div>
            </td>
            <td>
              <span class="badge badge-<?= $c['status'] ?>">
                <?= match($c['status']) {
                  'called'      => 'Dipanggil',
                  'in_progress' => 'Diperiksa',
                  'done'        => 'Selesai',
                  'waiting'     => 'Menunggu',
                  default       => $c['status']
                } ?>
              </span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php else: ?>
    <div class="empty-state" style="padding:28px">
      <p class="empty-state-title">Belum ada pemeriksaan hari ini</p>
    </div>
    <?php endif; ?>
  </div>

</div>

<!-- FIX: Panduan alur kerja perawat -->
<div class="card" style="margin-top:8px;border-color:var(--blue-muted);background:var(--blue-pale)">
  <div class="card-body" style="padding:14px 18px">
    <div style="font-size:12px;font-weight:700;color:var(--blue);margin-bottom:8px;text-transform:uppercase;letter-spacing:.06em">
      📋 Alur Pemeriksaan Awal
    </div>
    <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;font-size:12px;color:var(--gray-700)">
      <span style="background:var(--white);border:1px solid var(--blue-muted);border-radius:var(--r);padding:4px 10px">
        1. Pasien ambil antrian
      </span>
      <span style="color:var(--blue)">→</span>
      <span style="background:var(--white);border:1px solid var(--blue-muted);border-radius:var(--r);padding:4px 10px;font-weight:700;color:var(--blue)">
        2. Perawat input vital (halaman ini)
      </span>
      <span style="color:var(--blue)">→</span>
      <span style="background:var(--white);border:1px solid var(--blue-muted);border-radius:var(--r);padding:4px 10px">
        3. Dokter mulai periksa
      </span>
      <span style="color:var(--blue)">→</span>
      <span style="background:var(--white);border:1px solid var(--blue-muted);border-radius:var(--r);padding:4px 10px">
        4. Rekam medis selesai
      </span>
    </div>
  </div>
</div>

<?php
$pageContent = ob_get_clean();
require_once '../includes/layout.php';