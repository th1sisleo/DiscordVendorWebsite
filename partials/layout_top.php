<?php
/* Gemeinsamer Seitenkopf inkl. Navigation.
   Vor dem Einbinden setzen:
     $TITLE  = Seitentitel
     $BASE   = relativer Pfad zum Projekt-Root ('' im Root, '../' in Unterordnern)
     $ACTIVE = aktive Seite: home|queue|redeem|buy
*/
$BASE   = $BASE   ?? '';
$TITLE  = $TITLE  ?? 'CH3NO2';
$ACTIVE = $ACTIVE ?? '';

$nav = static function (string $key) use ($ACTIVE): string {
    return $ACTIVE === $key ? ' class="active"' : '';
};
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($TITLE, ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="stylesheet" href="<?= $BASE ?>style.css">
</head>
<body>

  <header class="nav">
    <div class="nav__inner">
      <a class="brand" href="<?= $BASE ?>index.php"><span class="brand__dot"></span>CH3NO2</a>
      <nav class="nav__links">
        <a href="<?= $BASE ?>index.php"<?= $nav('home') ?>>Home</a>
        <a href="<?= $BASE ?>queue/queue.php"<?= $nav('queue') ?>>Queue</a>
        <a href="<?= $BASE ?>key/redeem.php"<?= $nav('redeem') ?>>Key einlösen</a>
        <a href="<?= $BASE ?>buy/buy.php"<?= $nav('buy') ?>>Shop</a>
      </nav>
      <a class="btn btn--primary" href="<?= $BASE ?>dashboard/login.php">Dashboard</a>
    </div>
  </header>
