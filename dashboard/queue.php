<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Login-Sperre
$user   = require_login();
$ACTIVE = 'queue';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CH3NO2 — Queue</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <div class="dash">
    <?php require __DIR__ . '/partials/sidebar.php'; ?>

    <main class="dash__main">
      <h1>Queue</h1>
      <p class="sub">Deine aktuelle Warteschlange und der Verlauf.</p>

      <div class="status-grid">
        <div class="status-card">
          <h3>In der Warteschlange</h3>
          <div class="status-row">
            <span>Platzhalter-Eintrag</span>
            <span class="pill pill--ok">0/5 ●</span>
          </div>
          <div class="status-row">
            <span>Platzhalter-Eintrag</span>
            <span class="pill pill--idle">wartet</span>
          </div>
          <div class="status-row">
            <span>Platzhalter-Eintrag</span>
            <span class="pill pill--idle">wartet</span>
          </div>
        </div>

        <div class="status-card">
          <h3>Verlauf</h3>
          <div class="status-row">
            <span>Platzhalter-Verlaufseintrag Nummer eins</span>
            <span class="muted">Mo 09:13</span>
          </div>
          <div class="status-row">
            <span>Platzhalter-Verlaufseintrag Nummer zwei</span>
            <span class="muted">Mo 08:41</span>
          </div>
          <div class="status-row">
            <span>Platzhalter-Verlaufseintrag Nummer drei</span>
            <span class="muted">So 22:05</span>
          </div>
        </div>
      </div>
    </main>
  </div>

</body>
</html>
