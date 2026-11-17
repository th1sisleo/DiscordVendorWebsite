<?php
$TITLE  = 'CH3NO2 — Queue';
$BASE   = '../';
$ACTIVE = 'queue';
require __DIR__ . '/../partials/layout_top.php';
?>

  <main class="container" style="padding-top:60px; padding-bottom:60px;">
    <div class="section__head">
      <h2>Queue &amp; Status</h2>
      <p>Platzhalter-Übersicht über die aktuelle Warteschlange.</p>
    </div>

    <div class="status-grid">
      <div class="status-card">
        <h3>Queue</h3>
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

<?php require __DIR__ . '/../partials/layout_bottom.php'; ?>
