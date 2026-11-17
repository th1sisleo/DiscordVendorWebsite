<?php
declare(strict_types=1);
require __DIR__ . '/config.php';

// Login-Sperre
$user = require_login();

$error   = null;
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = (string) ($_POST['current'] ?? '');
    $new     = (string) ($_POST['new'] ?? '');
    $confirm = (string) ($_POST['confirm'] ?? '');

    if (!csrf_check($_POST['csrf'] ?? null)) {
        $error = 'Ungültige oder abgelaufene Sitzung. Bitte lade die Seite neu.';
    } else {
        [$ok, $err] = change_password((int) $user['id'], $current, $new, $confirm);
        if ($ok) {
            $success = true;
            session_regenerate_id(true); // neue Session-ID nach Passwortwechsel
        } else {
            $error = $err;
        }
    }
}
$ACTIVE = 'account';
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CH3NO2 — Konto</title>
  <link rel="stylesheet" href="../style.css">
</head>
<body>

  <div class="dash">
    <?php require __DIR__ . '/partials/sidebar.php'; ?>

    <main class="dash__main">
      <h1>Konto</h1>
      <p class="sub">Angemeldet als <strong><?= e($user['username']) ?></strong></p>

      <div class="panel panel--narrow" style="margin:0;">
        <h3 style="font-family:'Inter',sans-serif;margin-bottom:20px;">Passwort ändern</h3>

        <?php if ($success): ?>
          <div style="background:rgba(70,229,183,.12);border:1px solid var(--accent);
                      color:#aef5df;padding:12px 16px;border-radius:10px;margin-bottom:22px;
                      font-size:14px;">Dein Passwort wurde geändert.</div>
        <?php endif; ?>
        <?php if ($error): ?>
          <div style="background:rgba(244,91,105,.12);border:1px solid var(--danger);
                      color:#ffb3ba;padding:12px 16px;border-radius:10px;margin-bottom:22px;
                      font-size:14px;"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="account.php" autocomplete="off">
          <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
          <div class="field">
            <label for="current">Aktuelles Passwort</label>
            <input type="password" id="current" name="current" required maxlength="256">
          </div>
          <div class="field">
            <label for="new">Neues Passwort (mind. <?= MIN_PW_LENGTH ?> Zeichen)</label>
            <input type="password" id="new" name="new" required
                   minlength="<?= MIN_PW_LENGTH ?>" maxlength="256">
          </div>
          <div class="field">
            <label for="confirm">Neues Passwort wiederholen</label>
            <input type="password" id="confirm" name="confirm" required
                   minlength="<?= MIN_PW_LENGTH ?>" maxlength="256">
          </div>
          <button type="submit" class="btn btn--primary" style="width:100%;">Passwort ändern</button>
        </form>
      </div>
    </main>
  </div>

</body>
</html>
