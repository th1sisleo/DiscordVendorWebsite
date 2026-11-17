<?php
/* Dashboard-Seitenleiste.
   Vor dem Einbinden $ACTIVE setzen: overview|queue|redeem|account */
$ACTIVE = $ACTIVE ?? '';
$a = static fn (string $k): string => $ACTIVE === $k ? ' class="active"' : '';
?>
<aside class="sidebar">
  <a class="brand" href="dashboard.php"><span class="brand__dot"></span>CH3NO2</a>
  <nav>
    <a href="dashboard.php"<?= $a('overview') ?>>🏠 Übersicht</a>
    <a href="queue.php"<?= $a('queue') ?>>📊 Queue</a>
    <a href="redeem.php"<?= $a('redeem') ?>>🔑 Key einlösen</a>
    <a href="account.php"<?= $a('account') ?>>⚙️ Konto</a>
    <a href="logout.php">🚪 Abmelden</a>
  </nav>
  <a href="../index.php" style="display:block;margin-top:18px;padding:11px 14px;
     font-size:13px;color:var(--text-muted);border-top:1px solid var(--border);">↩ Zur Webseite</a>
</aside>
