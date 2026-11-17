<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Bereits eingeloggt? -> direkt zum Dashboard
if (current_user()) {
    header('Location: dashboard.php');
    exit;
}

$error  = null;
$locked = false;
$ip     = client_ip();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if (!csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Ungültige oder abgelaufene Sitzung. Bitte lade die Seite neu.';
    } elseif ($username === '' || $password === '') {
        $error = 'Bitte Benutzername und Passwort eingeben.';
    } elseif (recent_failed_attempts($username, $ip) >= MAX_ATTEMPTS) {
        $locked = true;
        $error  = 'Zu viele Fehlversuche. Bitte warte '
                . LOCKOUT_MINUTES . ' Minuten und versuche es erneut.';
    } else {
        $stmt = db()->prepare(
            'SELECT id, password_hash FROM users WHERE username = ?'
        );
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            record_attempt($username, $ip, true);

            // Hash bei Bedarf auf aktuelles Verfahren aktualisieren
            if (password_needs_rehash($user['password_hash'], PASSWORD_DEFAULT)) {
                $up = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
                $up->execute([password_hash($password, PASSWORD_DEFAULT), $user['id']]);
            }

            session_regenerate_id(true);      // Session Fixation verhindern
            $_SESSION['uid'] = (int) $user['id'];
            unset($_SESSION['csrf']);
            header('Location: dashboard.php');
            exit;
        }

        // Fehlschlag protokollieren (auch bei unbekanntem Benutzer,
        // damit kein Rückschluss möglich ist)
        record_attempt($username, $ip, false);
        $remaining = MAX_ATTEMPTS - recent_failed_attempts($username, $ip);
        $error = 'Benutzername oder Passwort ist falsch.';
        if ($remaining > 0) {
            $error .= ' Verbleibende Versuche: ' . $remaining;
        } else {
            $locked = true;
            $error  = 'Zu viele Fehlversuche. Konto für '
                    . LOCKOUT_MINUTES . ' Minuten gesperrt.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CH3NO2 — Login</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <header class="nav">
    <div class="nav__inner">
      <a class="brand" href="../index.html"><span class="brand__dot"></span>CH3NO2</a>
      <nav class="nav__links">
        <a href="../index.html">Home</a>
        <a href="../queue/queue.html">Queue</a>
        <a href="../key/redeem.html">Key einlösen</a>
        <a href="../buy/buy.html">Shop</a>
      </nav>
    </div>
  </header>

  <main class="container" style="padding-top:60px; padding-bottom:60px;">
    <div class="section__head">
      <h2>Anmelden</h2>
      <p>Geschützter Bereich — bitte melde dich an.</p>
    </div>

    <div class="panel panel--narrow">
      <?php if ($error): ?>
        <div style="background:rgba(244,91,105,.12);border:1px solid var(--danger);
                    color:#ffb3ba;padding:12px 16px;border-radius:10px;margin-bottom:22px;
                    font-size:14px;">
          <?= e($error) ?>
        </div>
      <?php endif; ?>

      <form method="post" action="login.php" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <div class="field">
          <label for="username">Benutzername</label>
          <input type="text" id="username" name="username" required
                 maxlength="64" autofocus
                 value="<?= e((string)($_POST['username'] ?? '')) ?>">
        </div>
        <div class="field">
          <label for="password">Passwort</label>
          <input type="password" id="password" name="password" required maxlength="256">
        </div>
        <button type="submit" class="btn btn--primary" style="width:100%;"
                <?= $locked ? 'disabled' : '' ?>>Anmelden</button>
      </form>

      <div class="panel__links">
        <a href="register.php">Noch kein Konto? Jetzt registrieren</a>
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
