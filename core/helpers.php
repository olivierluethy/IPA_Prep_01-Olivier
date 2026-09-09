<?php
/**
 * Nutze diese Funktion um einfach eine Ausgabe
 * mit htmlspecialchars() zu erstellen.
 */
function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', false);
}

/**
 * Nutze diese Funktion um auf einen POST-Wert
 * zuzugreifen.
 */
function post(string $key, $default = '')
{
    return $_POST[$key] ?? $default;
}

/**
 * Erzeugt ein einfaches Avatar-Bild (Initialen auf blauem Grund) als
 * data-URI. Wird für normal registrierte Nutzer verwendet, die kein
 * Google-Profilbild haben – so bleibt das Layout (Bild im Header) heil.
 */
function avatarDataUri(string $name): string
{
    $name = trim($name);
    $parts = preg_split('/\s+/', $name, -1, PREG_SPLIT_NO_EMPTY) ?: [];
    $initials = '';
    if (count($parts) >= 2) {
        $initials = mb_substr($parts[0], 0, 1) . mb_substr($parts[count($parts) - 1], 0, 1);
    } elseif (count($parts) === 1) {
        $initials = mb_substr($parts[0], 0, 2);
    } else {
        $initials = '?';
    }
    $initials = mb_strtoupper($initials);

    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120">'
        . '<rect width="120" height="120" fill="#0057D8"/>'
        . '<text x="50%" y="50%" dy=".1em" fill="#ffffff" font-family="Arial, Helvetica, sans-serif" '
        . 'font-size="52" font-weight="bold" text-anchor="middle" dominant-baseline="middle">'
        . e($initials) . '</text></svg>';

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

/**
 * Stellt eine Verbindung zur Datenbank her und gibt die
 * Datenbankverbindung als PDO zurück.
 */
$dbInstance = null;

function db(): PDO
{
    global $dbInstance;

    if ($dbInstance) {
        return $dbInstance;
    }

    try {
        $dbInstance = new PDO('mysql:host=127.0.0.1;fotostudio=' . $db['name'], $db['username'], $db['password'], [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ]);
    } catch (PDOException $e) {
        die('Keine Verbindung zur Datenbank möglich: ' . $e->getMessage());
    }
}