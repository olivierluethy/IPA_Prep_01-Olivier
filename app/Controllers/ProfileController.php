<?php

class ProfileController
{
    /* Startet die Session, falls noch keine läuft. */
    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* Prüft den CSRF-Token des Formulars gegen die Session. */
    private function checkCsrf()
    {
        $token = (string) post('csrf');
        return !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token);
    }

    /* Profil- und Einstellungsseite anzeigen. */
    public function profile()
    {
        requireLogin();

        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(16));
        }

        $user = (new Profile())->getById(currentUserId());
        if (!$user) {
            setFlash('error', 'Dein Benutzerkonto konnte nicht geladen werden.');
            header('Location: home');
            exit;
        }

        require 'app/Views/general/profile.view.php';
    }

    /* Kontodaten (und optional Profilbild) aktualisieren. */
    public function updateProfile()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->checkCsrf()) {
            setFlash('error', 'Sitzung abgelaufen. Bitte erneut versuchen.');
            header('Location: profile');
            exit;
        }

        $first = trim((string) post('first_name'));
        $last  = trim((string) post('last_name'));
        $email = trim((string) post('email'));

        if ($first === '' || $last === '' || $email === '') {
            setFlash('error', 'Bitte alle Felder ausfüllen.');
            header('Location: profile');
            exit;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            setFlash('error', 'Bitte eine gültige E-Mail-Adresse eingeben.');
            header('Location: profile');
            exit;
        }

        $profile = new Profile();

        if ($profile->emailTakenByOther($email, currentUserId())) {
            setFlash('error', 'Diese E-Mail-Adresse wird bereits verwendet.');
            header('Location: profile');
            exit;
        }

        /* Optionales Profilbild verarbeiten (Datei-Upload / Drag-and-drop oder Bild-URL). */
        $pictureError = $this->updatePictureFromRequest($profile);
        if ($pictureError !== null) {
            setFlash('error', $pictureError);
            header('Location: profile');
            exit;
        }

        $profile->updateAccount(currentUserId(), $first, $last, $email);

        $_SESSION['first_name'] = $first;
        $_SESSION['last_name']  = $last;
        $_SESSION['email']      = $email;
        $_SESSION['full_name']  = trim($first . ' ' . $last);

        setFlash('success', 'Profil aktualisiert.');
        header('Location: profile');
        exit;
    }

    /*
     * Ermittelt die Bildquelle aus dem Request:
     *  1. hochgeladene Datei ($_FILES['picture'] – auch per Drag-and-drop) oder
     *  2. eine Bild-URL (post('picture_url')).
     * Gibt null bei Erfolg (oder wenn kein Bild geändert wurde) bzw. eine
     * Fehlermeldung zurück.
     */
    private function updatePictureFromRequest(Profile $profile): ?string
    {
        // 1) Datei-Upload (Auswahl oder Drag-and-drop landen im selben Feld)
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
            $bytes = @file_get_contents($_FILES['picture']['tmp_name']);
            if ($bytes === false) {
                return 'Das Bild konnte nicht gelesen werden.';
            }
            return $this->storeImage($profile, $bytes);
        }
        if (isset($_FILES['picture']) && $_FILES['picture']['error'] !== UPLOAD_ERR_NO_FILE) {
            return 'Das Bild konnte nicht hochgeladen werden (evtl. zu gross).';
        }

        // 2) Bild-URL
        $url = trim((string) post('picture_url'));
        if ($url !== '') {
            [$bytes, $err] = $this->fetchImageFromUrl($url);
            if ($err !== null) {
                return $err;
            }
            return $this->storeImage($profile, $bytes);
        }

        return null; // kein Bild geändert
    }

    /* Validiert Bild-Bytes und speichert sie lokal unter public/uploads/. */
    private function storeImage(Profile $profile, string $bytes): ?string
    {
        if (strlen($bytes) > 3 * 1024 * 1024) {
            return 'Das Bild darf höchstens 3 MB gross sein.';
        }
        $info = @getimagesizefromstring($bytes);
        if ($info === false) {
            return 'Die Datei ist kein gültiges Bild (JPG, PNG, WEBP oder GIF).';
        }
        $map = [
            IMAGETYPE_JPEG => 'jpg',
            IMAGETYPE_PNG  => 'png',
            IMAGETYPE_WEBP => 'webp',
            IMAGETYPE_GIF  => 'gif',
        ];
        $ext = $map[$info[2]] ?? null;
        if ($ext === null) {
            return 'Bitte nutze ein JPG-, PNG-, WEBP- oder GIF-Bild.';
        }

        if (!is_dir('public/uploads')) {
            @mkdir('public/uploads', 0775, true);
        }
        $relative = 'public/uploads/u' . currentUserId() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;

        if (@file_put_contents($relative, $bytes) === false) {
            return 'Das Bild konnte nicht gespeichert werden.';
        }

        $profile->updatePicture(currentUserId(), $relative);
        $_SESSION['profileImageUrl'] = $relative;
        return null;
    }

    /* Lädt ein Bild von einer öffentlichen http(s)-URL (mit einfachem SSRF-Schutz). */
    private function fetchImageFromUrl(string $url): array
    {
        if (!preg_match('#^https?://#i', $url)) {
            return [null, 'Bitte gib eine gültige http(s)-Bild-URL an.'];
        }
        $host = parse_url($url, PHP_URL_HOST);
        if (!$host) {
            return [null, 'Die Bild-URL ist ungültig.'];
        }
        // Keine internen/privaten Adressen zulassen.
        $ip = gethostbyname($host);
        if (filter_var($ip, FILTER_VALIDATE_IP)
            && !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
            return [null, 'Diese Adresse ist nicht erlaubt.'];
        }

        $ctx = stream_context_create([
            'http' => ['timeout' => 8, 'follow_location' => 1, 'max_redirects' => 3, 'user_agent' => 'Lernjournal'],
            'https' => ['timeout' => 8, 'follow_location' => 1, 'max_redirects' => 3, 'user_agent' => 'Lernjournal'],
        ]);
        $bytes = @file_get_contents($url, false, $ctx, 0, 3 * 1024 * 1024 + 1);
        if ($bytes === false || $bytes === '') {
            return [null, 'Das Bild konnte von dieser URL nicht geladen werden.'];
        }
        return [$bytes, null];
    }

    /* Passwort ändern. */
    public function updatePassword()
    {
        requireLogin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$this->checkCsrf()) {
            setFlash('error', 'Sitzung abgelaufen. Bitte erneut versuchen.');
            header('Location: profile');
            exit;
        }

        $current = (string) post('current_password');
        $new     = (string) post('new_password');
        $confirm = (string) post('confirm_password');

        $profile = new Profile();
        $user    = $profile->getById(currentUserId());
        $hash    = $user['password'] ?? '';

        /* Aktuelles Passwort nur prüfen, wenn bereits eines gesetzt ist. */
        if ($hash !== '' && !password_verify($current, $hash)) {
            setFlash('error', 'Aktuelles Passwort ist falsch.');
            header('Location: profile');
            exit;
        }

        if (mb_strlen($new) < 6) {
            setFlash('error', 'Das neue Passwort muss mindestens 6 Zeichen lang sein.');
            header('Location: profile');
            exit;
        }
        if ($new !== $confirm) {
            setFlash('error', 'Die Passwörter stimmen nicht überein.');
            header('Location: profile');
            exit;
        }

        $profile->updatePassword(currentUserId(), password_hash($new, PASSWORD_DEFAULT));

        setFlash('success', 'Passwort aktualisiert.');
        header('Location: profile');
        exit;
    }
}
