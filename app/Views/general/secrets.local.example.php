<?php
/**
 * Vorlage für lokale Zugangsdaten.
 *
 * Zum Einrichten diese Datei nach `secrets.local.php` kopieren und die Werte
 * eintragen. `secrets.local.php` ist via .gitignore vom Repo ausgeschlossen.
 *
 * Alternativ können die Werte über die Umgebungsvariablen
 * GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET und GOOGLE_REDIRECT_URI gesetzt
 * werden; diese haben Vorrang vor der Datei.
 */
return [
    'client_id'     => 'DEINE_GOOGLE_CLIENT_ID.apps.googleusercontent.com',
    'client_secret' => 'DEIN_GOOGLE_CLIENT_SECRET',
    'redirect_uri'  => 'http://localhost/01-Olivier/home',
];
