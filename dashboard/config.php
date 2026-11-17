<?php
declare(strict_types=1);

/* ============================================================
   Zentrale Konfiguration: DB, Session, Security-Helfer
   ============================================================ */

// ---- Security-Header (vor jeglicher Ausgabe) ----
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: same-origin');
header("Content-Security-Policy: default-src 'self'; "
    . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
    . "font-src https://fonts.gstatic.com; "
    . "img-src 'self' data:; "
    . "form-action 'self'; frame-ancestors 'none'; base-uri 'self'");

// ---- Sichere Session ----
if (session_status() === PHP_SESSION_NONE) {
    $https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['SERVER_PORT'] ?? '') === '443');
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure'   => $https,
    ]);
    session_name('CH3NO2SESS');
    session_start();
}

// ---- Einstellungen ----
const DB_DIR          = __DIR__ . '/data';
const DB_PATH         = DB_DIR . '/app.db';
const MAX_ATTEMPTS     = 5;   // erlaubte Fehlversuche im Zeitfenster
const LOCKOUT_MINUTES  = 15;  // Zeitfenster und Sperrdauer in Minuten
const MIN_PW_LENGTH    = 8;
const MAX_REG_PER_HOUR = 5;   // max. Registrierungen pro IP und Stunde

// ---- Datenbank (SQLite-Datei) ----
function db(): PDO
{
    static $pdo = null;
    if ($pdo instanceof PDO) {
        return $pdo;
    }
    if (!is_dir(DB_DIR)) {
        mkdir(DB_DIR, 0770, true);
    }
    $pdo = new PDO('sqlite:' . DB_PATH, null, null, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
    $pdo->exec('PRAGMA journal_mode = WAL');
    $pdo->exec('PRAGMA foreign_keys = ON');
    init_schema($pdo);
    return $pdo;
}

function init_schema(PDO $pdo): void
{
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id            INTEGER PRIMARY KEY AUTOINCREMENT,
        username      TEXT NOT NULL UNIQUE COLLATE NOCASE,
        password_hash TEXT NOT NULL,
        created_at    TEXT NOT NULL DEFAULT (datetime('now'))
    )");
    $pdo->exec("CREATE TABLE IF NOT EXISTS login_attempts (
        id           INTEGER PRIMARY KEY AUTOINCREMENT,
        username     TEXT NOT NULL,
        ip           TEXT NOT NULL,
        success      INTEGER NOT NULL DEFAULT 0,
        attempted_at TEXT NOT NULL DEFAULT (datetime('now'))
    )");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_attempts
                ON login_attempts (username, ip, attempted_at)");
    $pdo->exec("CREATE TABLE IF NOT EXISTS reg_attempts (
        id         INTEGER PRIMARY KEY AUTOINCREMENT,
        ip         TEXT NOT NULL,
        created_at TEXT NOT NULL DEFAULT (datetime('now'))
    )");
    $pdo->exec("CREATE INDEX IF NOT EXISTS idx_reg ON reg_attempts (ip, created_at)");
}

// ---- Helfer ----
function e(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_check(mixed $token): bool
{
    return is_string($token)
        && !empty($_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $token);
}

function client_ip(): string
{
    return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
}

function current_user(): ?array
{
    if (empty($_SESSION['uid'])) {
        return null;
    }
    $stmt = db()->prepare('SELECT id, username, created_at FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['uid']]);
    $u = $stmt->fetch();
    return $u ?: null;
}

function require_login(): array
{
    $u = current_user();
    if (!$u) {
        header('Location: login.php');
        exit;
    }
    return $u;
}

/* Zählt fehlgeschlagene Logins für Benutzername ODER IP im Zeitfenster. */
function recent_failed_attempts(string $username, string $ip): int
{
    $stmt = db()->prepare(
        "SELECT COUNT(*) AS c FROM login_attempts
         WHERE success = 0 AND (username = :u OR ip = :ip)
           AND attempted_at >= datetime('now', :win)"
    );
    $stmt->execute([
        ':u'   => $username,
        ':ip'  => $ip,
        ':win' => '-' . LOCKOUT_MINUTES . ' minutes',
    ]);
    return (int) ($stmt->fetch()['c'] ?? 0);
}

function record_attempt(string $username, string $ip, bool $success): void
{
    $stmt = db()->prepare(
        'INSERT INTO login_attempts (username, ip, success) VALUES (?, ?, ?)'
    );
    $stmt->execute([$username, $ip, $success ? 1 : 0]);
}

// ---- Registrierung ----
function username_valid(string $u): bool
{
    return (bool) preg_match('/^[A-Za-z0-9_.-]{3,32}$/', $u);
}

function recent_registrations(string $ip): int
{
    $stmt = db()->prepare(
        "SELECT COUNT(*) AS c FROM reg_attempts
         WHERE ip = ? AND created_at >= datetime('now', '-60 minutes')"
    );
    $stmt->execute([$ip]);
    return (int) ($stmt->fetch()['c'] ?? 0);
}

function record_registration(string $ip): void
{
    $stmt = db()->prepare('INSERT INTO reg_attempts (ip) VALUES (?)');
    $stmt->execute([$ip]);
}

/**
 * Legt einen Benutzer an.
 * @return array{0: bool, 1: ?string}  [erfolg, fehlermeldung]
 */
function register_user(string $username, string $password, ?string $confirm = null): array
{
    $username = trim($username);
    if (!username_valid($username)) {
        return [false, 'Benutzername: 3–32 Zeichen, nur Buchstaben, Zahlen, . _ -'];
    }
    if (strlen($password) < MIN_PW_LENGTH) {
        return [false, 'Passwort muss mindestens ' . MIN_PW_LENGTH . ' Zeichen lang sein.'];
    }
    if ($confirm !== null && $password !== $confirm) {
        return [false, 'Die Passwörter stimmen nicht überein.'];
    }
    try {
        $stmt = db()->prepare(
            'INSERT INTO users (username, password_hash) VALUES (?, ?)'
        );
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
    } catch (PDOException $e) {
        if (str_contains($e->getMessage(), 'UNIQUE')) {
            return [false, 'Dieser Benutzername ist bereits vergeben.'];
        }
        throw $e;
    }
    return [true, null];
}

/**
 * Ändert das Passwort eines angemeldeten Benutzers.
 * @return array{0: bool, 1: ?string}  [erfolg, fehlermeldung]
 */
function change_password(int $uid, string $current, string $new, string $confirm): array
{
    $stmt = db()->prepare('SELECT password_hash FROM users WHERE id = ?');
    $stmt->execute([$uid]);
    $row = $stmt->fetch();

    if (!$row || !password_verify($current, $row['password_hash'])) {
        return [false, 'Das aktuelle Passwort ist falsch.'];
    }
    if (strlen($new) < MIN_PW_LENGTH) {
        return [false, 'Neues Passwort muss mindestens ' . MIN_PW_LENGTH . ' Zeichen lang sein.'];
    }
    if ($new !== $confirm) {
        return [false, 'Die neuen Passwörter stimmen nicht überein.'];
    }
    if (password_verify($new, $row['password_hash'])) {
        return [false, 'Das neue Passwort muss sich vom alten unterscheiden.'];
    }
    $up = db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $up->execute([password_hash($new, PASSWORD_DEFAULT), $uid]);
    return [true, null];
}
