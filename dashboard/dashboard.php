<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Login-Sperre: ohne gültige Session kein Zugriff
$user   = require_login();
$ACTIVE = 'overview';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CH3NO2 — Dashboard</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <div class="dash">
    <?php require __DIR__ . '/partials/sidebar.php'; ?>

    <main class="dash__main">
      <h1>Willkommen, <?= e($user['username']) ?> 👋</h1>
      <p class="sub">Angemeldet seit <?= e((string) $user['created_at']) ?> · Platzhalter-Status: aktuell nicht aktiv.</p>

      <div class="stat-grid">
        <div class="stat">
          <div class="label">Platzhalter-Kennzahl</div>
          <div class="value">0</div>
        </div>
        <div class="stat">
          <div class="label">Platzhalter-Kennzahl</div>
          <div class="value">0</div>
        </div>
        <div class="stat">
          <div class="label">Platzhalter-Kennzahl</div>
          <div class="value">0</div>
        </div>
        <div class="stat">
          <div class="label">Status</div>
          <div class="value" style="font-size:20px;color:var(--text-muted);">Inaktiv</div>
        </div>
      </div>

      <div class="status-card" style="margin-top:24px;">
        <h3>Letzte Aktivität</h3>
        <div class="status-row">
          <span>Platzhalter-Aktivität</span>
          <span class="muted">—</span>
        </div>
        <div class="status-row">
          <span>Platzhalter-Aktivität</span>
          <span class="muted">—</span>
        </div>
      </div>
    </main>
  </div>

</body>
</html>
