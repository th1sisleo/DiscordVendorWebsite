<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Bereits eingeloggt? -> direkt zum Dashboard
if (current_user()) {
    header('Location: dashboard.php');
    exit;
}

$error   = null;
$success = false;
$ip      = client_ip();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirm  = (string) ($_POST['confirm'] ?? '');

    if (!csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Ungültige oder abgelaufene Sitzung. Bitte lade die Seite neu.';
    } elseif (recent_registrations($ip) >= MAX_REG_PER_HOUR) {
        $error = 'Zu viele Registrierungen von dieser Adresse. Bitte später erneut versuchen.';
    } else {
        [$ok, $err] = register_user($username, $password, $confirm);
        if ($ok) {
            record_registration($ip);
            // Direkt anmelden
            $stmt = db()->prepare('SELECT id FROM users WHERE username = ?');
            $stmt->execute([$username]);
            $uid = (int) $stmt->fetch()['id'];
            session_regenerate_id(true);
            $_SESSION['uid'] = $uid;
            unset($_SESSION['csrf']);
            header('Location: dashboard.php');
            exit;
        }
        $error = $err;
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CH3NO2 — Registrieren</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <header class="nav">
    <div class="nav__inner">
      <a class="brand" href="../index.php"><span class="brand__dot"></span>CH3NO2</a>
      <nav class="nav__links">
        <a href="../index.php">Home</a>
        <a href="../queue/queue.php">Queue</a>
        <a href="../key/redeem.php">Key einlösen</a>
        <a href="../buy/buy.php">Shop</a>
      </nav>
    </div>
  </header>

  <main class="container" style="padding-top:60px; padding-bottom:60px;">
    <div class="section__head">
      <h2>Konto erstellen</h2>
      <p>Lege ein Konto an, um auf das Dashboard zuzugreifen.</p>
    </div>

    <div class="panel panel--narrow">
      <?php if ($error): ?>
        <div style="background:rgba(244,91,105,.12);border:1px solid var(--danger);
                    color:#ffb3ba;padding:12px 16px;border-radius:10px;margin-bottom:22px;
                    font-size:14px;"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post" action="register.php" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <div class="field">
          <label for="username">Benutzername</label>
          <input type="text" id="username" name="username" required
                 minlength="3" maxlength="32" autofocus
                 value="<?= e((string)($_POST['username'] ?? '')) ?>">
        </div>
        <div class="field">
          <label for="password">Passwort (mind. <?= MIN_PW_LENGTH ?> Zeichen)</label>
          <input type="password" id="password" name="password" required
                 minlength="<?= MIN_PW_LENGTH ?>" maxlength="256">
        </div>
        <div class="field">
          <label for="confirm">Passwort wiederholen</label>
          <input type="password" id="confirm" name="confirm" required
                 minlength="<?= MIN_PW_LENGTH ?>" maxlength="256">
        </div>
        <button type="submit" class="btn btn--primary" style="width:100%;">Konto erstellen</button>
      </form>

      <div class="panel__links">
        <a href="login.php">Bereits ein Konto? Zum Login</a>
      </div>
    </div>
  </main>

  <footer class="footer">
    <div class="container footer__inner">
      <span>© 2026 CH3NO2 — Platzhalter-Footer</span>
      <span>Geschützter Bereich</span>
    </div>
  </footer>

</body>
</html>
