<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/navside.css">
    <link rel="stylesheet" href="public/fontawesome/css/all.css">
    <link rel="stylesheet" href="public/css/home.css">
    <link rel="stylesheet" href="public/css/auth.css">
    <link rel="shortcut icon" href="images/favicon.ico">
    <title>Journal - Login</title>
</head>

<body>
<?php
$actual_link = basename(__FILE__); // aktueller dateiname (wird für header.php benötigt)
include ("navside.view.php");

// CSRF-Token für die Formulare vorbereiten (Session läuft bereits über config.php)
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

// Fehler aus einem fehlgeschlagenen Versuch übernehmen und danach verwerfen
$authError = $_SESSION['auth_error'] ?? '';
$authMode  = $_SESSION['auth_mode'] ?? 'login';
unset($_SESSION['auth_error'], $_SESSION['auth_mode']);

$googleUrl = isset($client) ? $client->createAuthUrl() : '#';
?>

    <main>
        <!-- main content goes here -->
        <h1>Welcome To Journal <br> Web-App</h1>

        <div class="authActions">
            <button type="button" class="loginBtn" onclick="openAuthModal('login')">Anmelden</button>
            <button type="button" class="loginBtn loginBtn--outline" onclick="openAuthModal('register')">Registrieren</button>
        </div>
    </main>

    <!-- Login / Registrieren Modal -->
    <div class="auth-overlay" id="authOverlay" aria-hidden="true">
        <div class="auth-modal" role="dialog" aria-modal="true" aria-labelledby="authTitle">
            <button type="button" class="auth-close" aria-label="Schließen" onclick="closeAuthModal()">&times;</button>

            <div class="auth-tabs">
                <button type="button" class="auth-tab" data-tab="login" onclick="switchAuthTab('login')">Anmelden</button>
                <button type="button" class="auth-tab" data-tab="register" onclick="switchAuthTab('register')">Registrieren</button>
            </div>

            <!-- Anmelden -->
            <form class="auth-panel" data-panel="login" action="authenticate" method="post" autocomplete="on">
                <h2 id="authTitle" class="auth-heading">Willkommen zurück</h2>

                <?php if ($authError !== '' && $authMode === 'login'): ?>
                    <p class="auth-error"><i class="fa-solid fa-circle-exclamation"></i> <?= e($authError) ?></p>
                <?php endif; ?>

                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

                <label class="auth-field">
                    <span>E-Mail</span>
                    <input type="email" name="email" placeholder="name@beispiel.ch" required autocomplete="email">
                </label>
                <label class="auth-field">
                    <span>Passwort</span>
                    <input type="password" name="password" placeholder="••••••••" required autocomplete="current-password">
                </label>

                <button type="submit" class="auth-submit">Anmelden</button>

                <div class="auth-divider"><span>oder</span></div>

                <a class="auth-google" href="<?= e($googleUrl) ?>">
                    <i class="fa-brands fa-google"></i> Mit Google anmelden
                </a>

                <p class="auth-switch">Noch kein Konto?
                    <button type="button" onclick="switchAuthTab('register')">Jetzt registrieren</button>
                </p>
            </form>

            <!-- Registrieren -->
            <form class="auth-panel" data-panel="register" action="register" method="post" autocomplete="on">
                <h2 class="auth-heading">Konto erstellen</h2>

                <?php if ($authError !== '' && $authMode === 'register'): ?>
                    <p class="auth-error"><i class="fa-solid fa-circle-exclamation"></i> <?= e($authError) ?></p>
                <?php endif; ?>

                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

                <div class="auth-row">
                    <label class="auth-field">
                        <span>Vorname</span>
                        <input type="text" name="first_name" placeholder="Olivier" required autocomplete="given-name">
                    </label>
                    <label class="auth-field">
                        <span>Nachname</span>
                        <input type="text" name="last_name" placeholder="Lüthy" required autocomplete="family-name">
                    </label>
                </div>
                <label class="auth-field">
                    <span>E-Mail</span>
                    <input type="email" name="email" placeholder="name@beispiel.ch" required autocomplete="email">
                </label>
                <label class="auth-field">
                    <span>Passwort</span>
                    <input type="password" name="password" placeholder="Mindestens 6 Zeichen" minlength="6" required autocomplete="new-password">
                </label>
                <label class="auth-field">
                    <span>Rolle</span>
                    <select name="role">
                        <option value="0" selected>Lernender</option>
                        <option value="1">Fachkraft</option>
                        <option value="2">Administrator</option>
                    </select>
                </label>

                <button type="submit" class="auth-submit">Registrieren</button>

                <p class="auth-switch">Schon ein Konto?
                    <button type="button" onclick="switchAuthTab('login')">Zur Anmeldung</button>
                </p>
            </form>
        </div>
    </div>

    <script src="public/js/app.js"></script>
    <script>
        (function () {
            var overlay = document.getElementById('authOverlay');

            window.openAuthModal = function (tab) {
                switchAuthTab(tab || 'login');
                overlay.classList.add('is-open');
                overlay.setAttribute('aria-hidden', 'false');
            };

            window.closeAuthModal = function () {
                overlay.classList.remove('is-open');
                overlay.setAttribute('aria-hidden', 'true');
            };

            window.switchAuthTab = function (tab) {
                var tabs = document.querySelectorAll('.auth-tab');
                var panels = document.querySelectorAll('.auth-panel');
                for (var i = 0; i < tabs.length; i++) {
                    tabs[i].classList.toggle('is-active', tabs[i].getAttribute('data-tab') === tab);
                }
                for (var j = 0; j < panels.length; j++) {
                    panels[j].classList.toggle('is-active', panels[j].getAttribute('data-panel') === tab);
                }
            };

            // Schließen bei Klick auf den Hintergrund oder ESC
            overlay.addEventListener('click', function (event) {
                if (event.target === overlay) {
                    closeAuthModal();
                }
            });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeAuthModal();
                }
            });

            // Nach einem fehlgeschlagenen Versuch das Modal automatisch wieder öffnen
            <?php if ($authError !== ''): ?>
            openAuthModal(<?= json_encode($authMode) ?>);
            <?php else: ?>
            switchAuthTab('login');
            <?php endif; ?>
        })();
    </script>

</body>

</html>
