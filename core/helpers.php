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
        . '<rect width="120" height="120" fill="#4f46e5"/>'
        . '<text x="50%" y="50%" dy=".1em" fill="#ffffff" font-family="Arial, Helvetica, sans-serif" '
        . 'font-size="52" font-weight="bold" text-anchor="middle" dominant-baseline="middle">'
        . e($initials) . '</text></svg>';

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}

/* ---------------------------------------------------------------------------
 * Authentifizierung / Session
 * ------------------------------------------------------------------------- */

function isLoggedIn(): bool
{
    return !empty($_SESSION['loggedin']);
}

function currentUserId(): int
{
    return (int) ($_SESSION['id'] ?? 0);
}

function currentRole(): int
{
    return (int) ($_SESSION['role'] ?? -1);
}

/** Erzwingt einen eingeloggten Nutzer, sonst Weiterleitung zum Login. */
function requireLogin(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isLoggedIn()) {
        header('Location: login');
        exit;
    }
}

/** Erzwingt eine bestimmte Rolle (0=Lernender, 1=Fachkraft, 2=Admin). */
function requireRole(array $roles): void
{
    requireLogin();
    if (!in_array(currentRole(), $roles, true)) {
        header('Location: home');
        exit;
    }
}

/* ---------------------------------------------------------------------------
 * Flash-Meldungen (einmalige Hinweise nach einer Aktion)
 * ------------------------------------------------------------------------- */

function setFlash(string $type, string $message): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function renderFlash(): string
{
    if (empty($_SESSION['flash'])) {
        return '';
    }
    $f = $_SESSION['flash'];
    unset($_SESSION['flash']);

    $map = [
        'success' => ['bg-emerald-50 text-emerald-800 ring-emerald-200', 'fa-circle-check text-emerald-500'],
        'error'   => ['bg-rose-50 text-rose-800 ring-rose-200',          'fa-circle-exclamation text-rose-500'],
        'info'    => ['bg-brand-50 text-brand-700 ring-brand-200',       'fa-circle-info text-brand-500'],
    ];
    [$cls, $icon] = $map[$f['type']] ?? $map['info'];

    return '<div class="mb-6 flex items-start gap-3 rounded-xl px-4 py-3 text-sm ring-1 ' . $cls . '">'
         . '<i class="fa-solid ' . $icon . ' mt-0.5"></i><span>' . e($f['message']) . '</span></div>';
}

/* ---------------------------------------------------------------------------
 * Rich-Text (CKEditor-Inhalte) sicher ausgeben
 * ------------------------------------------------------------------------- */

function richtext(?string $html): string
{
    $html = (string) $html;
    // Altbestand: falls der Inhalt escaped gespeichert wurde, zurückwandeln.
    if (strpos($html, '&lt;') !== false && strpos($html, '<') === false) {
        $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');
    }
    // Best-Effort-Schutz: <script>, on*-Attribute und javascript: entfernen.
    $html = preg_replace('#<script\b[^>]*>.*?</script>#is', '', $html);
    $html = preg_replace('#\son\w+\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)#i', '', $html);
    $html = preg_replace('#javascript:#i', '', $html);
    return $html;
}

/* ---------------------------------------------------------------------------
 * Kleine UI-Helfer
 * ------------------------------------------------------------------------- */

/** Status-Badge (0 = Entwurf, 1 = Freigegeben). */
function statusBadge(int $status): string
{
    if ($status === 1) {
        return '<span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-medium text-emerald-700 ring-1 ring-emerald-200">'
             . '<i class="fa-solid fa-circle-check text-[10px]"></i> Freigegeben</span>';
    }
    return '<span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-700 ring-1 ring-amber-200">'
         . '<i class="fa-solid fa-pen-ruler text-[10px]"></i> Entwurf</span>';
}

/** Datum als d.m.Y formatieren. */
function formatDate(?string $datetime): string
{
    if (!$datetime) {
        return '';
    }
    $ts = strtotime($datetime);
    return $ts ? date('d.m.Y', $ts) : e($datetime);
}

/** Kurzer Textauszug (ohne HTML) aus einem Rich-Text-Feld. */
function excerpt(string $html, int $len = 160): string
{
    $text = trim(preg_replace('/\s+/', ' ', strip_tags(richtext($html))));
    if (mb_strlen($text) <= $len) {
        return $text;
    }
    return mb_substr($text, 0, $len) . '…';
}