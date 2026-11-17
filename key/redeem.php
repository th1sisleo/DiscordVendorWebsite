<?php
$TITLE  = 'CH3NO2 — Key einlösen';
$BASE   = '../';
$ACTIVE = 'redeem';
require __DIR__ . '/../partials/layout_top.php';
?>

  <main class="container" style="padding-top:60px; padding-bottom:60px;">
    <div class="section__head">
      <h2>Key einlösen</h2>
      <p>Platzhalter-Formular — gib hier deine Platzhalter-Daten ein.</p>
    </div>

    <div class="panel panel--narrow">
      <form onsubmit="return false;">
        <div class="field">
          <label for="username">Benutzername</label>
          <input type="text" id="username" name="username" placeholder="Platzhalter-Benutzername" required>
        </div>
        <div class="field">
          <label for="key">Key</label>
          <input type="text" id="key" name="key" placeholder="XXXX-XXXX-XXXX-XXXX" required>
        </div>
        <button type="submit" class="btn btn--primary" style="width:100%;">Einlösen</button>
      </form>

      <div class="panel__links">
        <a href="#">Platzhalter-Hilfe</a>
        <a href="#">Support</a>
        <a href="../buy/buy.php">Zum Shop</a>
      </div>
    </div>
  </main>

<?php require __DIR__ . '/../partials/layout_bottom.php'; ?>
