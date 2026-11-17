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

$username = trim($username);
if ($username === '' || mb_strlen($username) > 64) {
    fwrite(STDERR, "Benutzername muss 1–64 Zeichen lang sein.\n");
    exit(1);
}
if (strlen($password) < MIN_PW_LENGTH) {
    fwrite(STDERR, "Passwort muss mindestens " . MIN_PW_LENGTH . " Zeichen lang sein.\n");
    exit(1);
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = db()->prepare(
        'INSERT INTO users (username, password_hash) VALUES (?, ?)'
    );
    $stmt->execute([$username, $hash]);
    echo "Benutzer '{$username}' wurde angelegt.\n";
} catch (PDOException $e) {
    if (str_contains($e->getMessage(), 'UNIQUE')) {
        fwrite(STDERR, "Benutzer '{$username}' existiert bereits.\n");
        exit(1);
    }
    throw $e;
}
