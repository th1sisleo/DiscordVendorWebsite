<?php
$TITLE  = 'CH3NO2 — Shop';
$BASE   = '../';
$ACTIVE = 'buy';
require __DIR__ . '/../partials/layout_top.php';
?>

  <main class="container" style="padding-top:60px; padding-bottom:60px;">
    <div class="section__head">
      <h2>Shop</h2>
      <p>Platzhalter-Preispläne — passe Titel, Preise und Features an.</p>
    </div>

    <div class="grid">
      <article class="card">
        <h3>Starter</h3>
        <p style="font-family:'Poppins',sans-serif;font-size:32px;color:var(--text);margin:10px 0;">0 €</p>
        <p>Platzhalter-Feature eins<br>Platzhalter-Feature zwei<br>Platzhalter-Feature drei</p>
        <a class="btn btn--ghost" href="#" style="width:100%;margin-top:18px;">Auswählen</a>
      </article>
      <article class="card" style="border-color:var(--brand);">
        <div class="card__icon">★</div>
        <h3>Pro</h3>
        <p style="font-family:'Poppins',sans-serif;font-size:32px;color:var(--text);margin:10px 0;">0 €</p>
        <p>Platzhalter-Feature eins<br>Platzhalter-Feature zwei<br>Platzhalter-Feature drei</p>
        <a class="btn btn--primary" href="#" style="width:100%;margin-top:18px;">Auswählen</a>
      </article>
      <article class="card">
        <h3>Ultimate</h3>
        <p style="font-family:'Poppins',sans-serif;font-size:32px;color:var(--text);margin:10px 0;">0 €</p>
        <p>Platzhalter-Feature eins<br>Platzhalter-Feature zwei<br>Platzhalter-Feature drei</p>
        <a class="btn btn--ghost" href="#" style="width:100%;margin-top:18px;">Auswählen</a>
      </article>
    </div>
  </main>

<?php require __DIR__ . '/../partials/layout_bottom.php'; ?>
