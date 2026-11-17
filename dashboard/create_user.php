<?php
declare(strict_types=1);

/* ============================================================
   Benutzer anlegen — nur über die Kommandozeile ausführbar.
   Aufruf:  php create_user.php <benutzername> <passwort>
   ============================================================ */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit('Dieses Skript darf nur über die Kommandozeile ausgeführt werden.');
}

require __DIR__ . '/config.php';

$username = $argv[1] ?? null;
$password = $argv[2] ?? null;

if ($username === null || $password === null) {
    fwrite(STDERR, "Aufruf: php create_user.php <benutzername> <passwort>\n");
    exit(1);
}

[$ok, $err] = register_user(trim($username), $password);
if (!$ok) {
    fwrite(STDERR, $err . "\n");
    exit(1);
}
echo "Benutzer '" . trim($username) . "' wurde angelegt.\n";
