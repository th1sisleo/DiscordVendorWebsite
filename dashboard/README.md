# Dashboard-Login (PHP + SQLite)

Geschützter Dashboard-Bereich mit Benutzername/Passwort-Login, gespeichert in
einer SQLite-Datenbankdatei.

## Voraussetzungen
- PHP 8.1+ mit `pdo_sqlite` (geprüft mit PHP 8.3)

## Erster Start

1. **Benutzer anlegen** – entweder per CLI …
   ```bash
   php dashboard/create_user.php <benutzername> <passwort>
   ```
   … oder im Browser über `register.php`. Das Passwort muss mindestens 8 Zeichen
   lang sein. Beim ersten Aufruf wird die Datenbank `dashboard/data/app.db`
   automatisch angelegt.

2. **Server starten** (zum Testen) – aus dem **Projekt-Root**:
   ```bash
   php -S 127.0.0.1:8000 -t .
   ```
   Dann im Browser `http://127.0.0.1:8000/index.php` öffnen.

Die gesamte Seite läuft über PHP. Gemeinsame Navbar/Footer liegen in
`partials/` und werden per `require` eingebunden.

## Dateien
| Datei               | Zweck                                                      |
|---------------------|------------------------------------------------------------|
| `config.php`        | DB-Verbindung, Session, Security-Header, Hilfsfunktionen   |
| `login.php`         | Login-Formular inkl. CSRF-Schutz und Brute-Force-Sperre    |
| `register.php`      | Selbstregistrierung (mit IP-Limit) + Auto-Login            |
| `account.php`       | Passwort ändern (nur für angemeldete Nutzer)               |
| `dashboard.php`     | Geschützte Seite (ohne Login → Weiterleitung zum Login)    |
| `logout.php`        | Session beenden                                            |
| `create_user.php`   | Benutzer anlegen (nur CLI)                                 |
| `data/app.db`       | SQLite-Datenbank (wird nicht eingecheckt)                  |

## Sicherheitsmechanismen
- **Passwörter**: nur als Hash gespeichert (`password_hash`, bcrypt/argon2),
  automatisches Rehashing bei veralteten Hashes.
- **SQL-Injection**: ausschließlich Prepared Statements (PDO).
- **Brute Force**: nach 5 Fehlversuchen (pro Benutzer **oder** IP) 15 Minuten
  gesperrt. Werte in `config.php` anpassbar (`MAX_ATTEMPTS`, `LOCKOUT_MINUTES`).
- **Registrierung**: pro IP max. 5 neue Konten/Stunde (`MAX_REG_PER_HOUR`),
  Benutzername auf `[A-Za-z0-9_.-]`, 3–32 Zeichen begrenzt.
- **Passwortwechsel**: aktuelles Passwort erforderlich, neue Session-ID danach.
- **CSRF**: Token pro Session, Prüfung mit `hash_equals`.
- **Sessions**: HttpOnly- und SameSite-Cookie, `Secure` automatisch unter HTTPS,
  Session-ID-Regeneration nach erfolgreichem Login.
- **XSS**: jede Ausgabe über `e()` (`htmlspecialchars`).
- **Header**: `Content-Security-Policy`, `X-Frame-Options`, `nosniff`,
  `Referrer-Policy`.
- **DB-Schutz**: `data/.htaccess` verbietet den direkten Download der DB-Datei
  (Apache). Noch sicherer: den Ordner `data/` außerhalb des Web-Roots ablegen
  und `DB_DIR` in `config.php` anpassen.

## Hinweise für den Produktivbetrieb
- Nur über **HTTPS** betreiben (dann wird das Session-Cookie als `Secure`
  gesetzt).
- Bei nginx o. Ä. den Zugriff auf `*.db` bzw. den `data/`-Ordner selbst sperren
  (die `.htaccess` wirkt nur unter Apache).
