<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Login-Sperre
$user   = require_login();
$ACTIVE = 'redeem';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CH3NO2 — Key einlösen</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <div class="dash">
    <?php require __DIR__ . '/partials/sidebar.php'; ?>

    <main class="dash__main">
      <h1>Key einlösen</h1>
      <p class="sub">Platzhalter-Formular — gib hier deine Platzhalter-Daten ein.</p>

      <div class="panel panel--narrow" style="margin:0;">
        <form onsubmit="return false;">
          <div class="field">
            <label for="key">Key</label>
            <input type="text" id="key" name="key" placeholder="XXXX-XXXX-XXXX-XXXX" required>
          </div>
          <button type="submit" class="btn btn--primary" style="width:100%;">Einlösen</button>
        </form>
      </div>
    </main>
  </div>

</body>
</html>
