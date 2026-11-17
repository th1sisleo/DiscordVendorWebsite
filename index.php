<?php
$TITLE  = 'CH3NO2 — Startseite';
$BASE   = '';
$ACTIVE = 'home';
require __DIR__ . '/partials/layout_top.php';
?>

  <main class="container">

    <!-- Hero -->
    <section class="hero">
      <div class="hero__text">
        <span class="hero__badge">★ Platzhalter-Badge</span>
        <h1>Dein Titel <span class="grad">hier einfügen</span></h1>
        <p class="lead">
          Dies ist ein Platzhalter-Text für deine Startseite. Beschreibe hier
          in ein, zwei Sätzen, worum es auf deiner Seite geht.
        </p>
        <div class="hero__cta">
          <a class="btn btn--primary" href="buy/buy.php">Jetzt loslegen</a>
          <a class="btn btn--ghost" href="#faq">Mehr erfahren</a>
        </div>
      </div>
      <div class="hero__art">
        <img src="bilder/wumpus1.png" alt="Illustration">
      </div>
    </section>

    <!-- FAQ -->
    <section class="section" id="faq">
      <div class="section__head">
        <h2>Häufige Fragen</h2>
        <p>Platzhalter-Untertitel für den Abschnitt.</p>
      </div>
      <div class="grid">
        <article class="card">
          <div class="card__icon">?</div>
          <h3>Frage als Platzhalter</h3>
          <p>Hier steht eine Platzhalter-Antwort. Ersetze diesen Text durch deinen eigenen Inhalt.</p>
        </article>
        <article class="card">
          <div class="card__icon">?</div>
          <h3>Zweite Platzhalter-Frage</h3>
          <p>Hier steht eine Platzhalter-Antwort. Ersetze diesen Text durch deinen eigenen Inhalt.</p>
        </article>
        <article class="card">
          <div class="card__icon">?</div>
          <h3>Dritte Platzhalter-Frage</h3>
          <p>Hier steht eine Platzhalter-Antwort. Ersetze diesen Text durch deinen eigenen Inhalt.</p>
        </article>
        <article class="card">
          <div class="card__icon">?</div>
          <h3>Vierte Platzhalter-Frage</h3>
          <p>Hier steht eine Platzhalter-Antwort. Ersetze diesen Text durch deinen eigenen Inhalt.</p>
        </article>
      </div>
    </section>

    <!-- Vouches -->
    <section class="section" id="vouches">
      <div class="section__head">
        <h2>Bewertungen</h2>
        <p>Platzhalter für Kundenstimmen.</p>
      </div>
      <div class="grid">
        <article class="vouch">
          <div class="vouch__top">
            <div class="vouch__avatar">T</div>
            <div>
              <div class="vouch__name">Thisisleo</div>
              <div class="vouch__role">Platzhalter-Rolle</div>
            </div>
          </div>
          <div class="stars">★★★★★</div>
          <p>Platzhalter-Bewertungstext. Hier kann ein Zitat oder ein Screenshot stehen.</p>
          <img src="bilder/vouch.PNG" alt="Bewertung">
        </article>
        <article class="vouch">
          <div class="vouch__top">
            <div class="vouch__avatar">N</div>
            <div>
              <div class="vouch__name">Name</div>
              <div class="vouch__role">Platzhalter-Rolle</div>
            </div>
          </div>
          <div class="stars">★★★★★</div>
          <p>Platzhalter-Bewertungstext. Ersetze diesen Abschnitt durch echten Inhalt.</p>
        </article>
        <article class="vouch">
          <div class="vouch__top">
            <div class="vouch__avatar">N</div>
            <div>
              <div class="vouch__name">Name</div>
              <div class="vouch__role">Platzhalter-Rolle</div>
            </div>
          </div>
          <div class="stars">★★★★★</div>
          <p>Platzhalter-Bewertungstext. Ersetze diesen Abschnitt durch echten Inhalt.</p>
        </article>
      </div>
    </section>

  </main>

<?php require __DIR__ . '/partials/layout_bottom.php'; ?>
