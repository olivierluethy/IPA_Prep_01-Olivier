<?php
// Session läuft bereits über config.php
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(16));
}
$csrf = $_SESSION['csrf'];

$authError = $_SESSION['auth_error'] ?? '';
$authMode  = $_SESSION['auth_mode'] ?? 'login';
unset($_SESSION['auth_error'], $_SESSION['auth_mode']);

$googleUrl = isset($client) ? $client->createAuthUrl() : '#';
?>
<!DOCTYPE html>
<html lang="de" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lernjournal · Anmelden</title>
    <link rel="shortcut icon" href="images/favicon.ico">
    <link rel="stylesheet" href="public/fontawesome/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:opsz,wght@8..60,500;8..60,600;8..60,700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: {
                colors: {
                    brand: { 50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',500:'#6366f1',600:'#4f46e5',700:'#4338ca',900:'#312e81',950:'#1e1b4b' },
                    ink: '#0f172a',
                },
                fontFamily: { sans:['Inter','system-ui','sans-serif'], serif:['"Source Serif 4"','Georgia','serif'] },
            } },
        };
    </script>
</head>

<body class="h-full bg-slate-50 font-sans text-ink antialiased">
<div class="flex min-h-full">

    <!-- Marken-Panel -->
    <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-brand-950 p-12 text-white lg:flex">
        <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-brand-600/20 blur-3xl"></div>
        <div class="absolute -bottom-32 -left-16 h-96 w-96 rounded-full bg-brand-500/10 blur-3xl"></div>

        <div class="relative flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600">
                <i class="fa-solid fa-book-open"></i>
            </span>
            <span class="font-serif text-xl font-semibold">Lernjournal</span>
        </div>

        <div class="relative max-w-md">
            <h1 class="font-serif text-4xl font-semibold leading-tight">
                Deine Ausbildung,<br>sauber dokumentiert.
            </h1>
            <p class="mt-4 text-brand-200">
                Halte Tages- und Wochenberichte fest, verschlagworte deine Arbeiten
                und gib sie mit einem Klick an deine Fachkraft frei.
            </p>

            <ul class="mt-8 space-y-4 text-sm">
                <li class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10"><i class="fa-solid fa-calendar-day"></i></span>
                    Tagesberichte in Sekunden erfassen
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10"><i class="fa-solid fa-tags"></i></span>
                    Arbeiten mit eigenen Keywords ordnen
                </li>
                <li class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-white/10"><i class="fa-solid fa-circle-check"></i></span>
                    Wochenrückblick und Freigabe an die Fachkraft
                </li>
            </ul>
        </div>

        <p class="relative text-xs text-brand-200/70">© <?= date('Y') ?> Lernjournal · IPA</p>
    </div>

    <!-- Formular-Bereich -->
    <div class="flex w-full flex-col justify-center px-6 py-12 sm:px-12 lg:w-1/2">
        <div class="mx-auto w-full max-w-sm">

            <div class="mb-8 flex items-center gap-3 lg:hidden">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-600 text-white"><i class="fa-solid fa-book-open"></i></span>
                <span class="font-serif text-xl font-semibold">Lernjournal</span>
            </div>

            <!-- Tabs -->
            <div class="mb-6 grid grid-cols-2 gap-1 rounded-xl bg-slate-100 p-1">
                <button type="button" data-tab="login" onclick="switchTab('login')"
                        class="auth-tab rounded-lg px-4 py-2 text-sm font-medium transition">Anmelden</button>
                <button type="button" data-tab="register" onclick="switchTab('register')"
                        class="auth-tab rounded-lg px-4 py-2 text-sm font-medium transition">Registrieren</button>
            </div>

            <?php if ($authError !== ''): ?>
                <div id="authError" class="mb-5 flex items-start gap-3 rounded-xl bg-rose-50 px-4 py-3 text-sm text-rose-700 ring-1 ring-rose-200">
                    <i class="fa-solid fa-circle-exclamation mt-0.5"></i><span><?= e($authError) ?></span>
                </div>
            <?php endif; ?>

            <!-- Anmelden -->
            <form data-panel="login" action="authenticate" method="post" class="auth-panel space-y-4">
                <div>
                    <h2 class="font-serif text-2xl font-semibold">Willkommen zurück</h2>
                    <p class="mt-1 text-sm text-slate-500">Melde dich mit deinem Konto an.</p>
                </div>
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <label class="block">
                    <span class="mb-1.5 block text-sm font-medium text-slate-700">E-Mail</span>
                    <div class="relative">
                        <i class="fa-solid fa-envelope pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="email" name="email" required autocomplete="email" placeholder="name@beispiel.ch"
                               class="w-full rounded-lg border border-slate-300 py-2.5 pl-10 pr-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                    </div>
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-sm font-medium text-slate-700">Passwort</span>
                    <div class="relative">
                        <i class="fa-solid fa-lock pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="password" name="password" required autocomplete="current-password" placeholder="••••••••"
                               class="w-full rounded-lg border border-slate-300 py-2.5 pl-10 pr-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                    </div>
                </label>
                <button type="submit"
                        class="w-full rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    Anmelden
                </button>

                <div class="flex items-center gap-3 py-1 text-xs text-slate-400">
                    <span class="h-px flex-1 bg-slate-200"></span> oder <span class="h-px flex-1 bg-slate-200"></span>
                </div>
                <a href="<?= e($googleUrl) ?>"
                   class="flex w-full items-center justify-center gap-2.5 rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    <i class="fa-brands fa-google text-[#db4437]"></i> Mit Google anmelden
                </a>
            </form>

            <!-- Registrieren -->
            <form data-panel="register" action="register" method="post" class="auth-panel space-y-4">
                <div>
                    <h2 class="font-serif text-2xl font-semibold">Konto erstellen</h2>
                    <p class="mt-1 text-sm text-slate-500">Starte dein digitales Lernjournal.</p>
                </div>
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <div class="grid grid-cols-2 gap-3">
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-medium text-slate-700">Vorname</span>
                        <input type="text" name="first_name" required autocomplete="given-name" placeholder="Olivier"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                    </label>
                    <label class="block">
                        <span class="mb-1.5 block text-sm font-medium text-slate-700">Nachname</span>
                        <input type="text" name="last_name" required autocomplete="family-name" placeholder="Lüthy"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                    </label>
                </div>
                <label class="block">
                    <span class="mb-1.5 block text-sm font-medium text-slate-700">E-Mail</span>
                    <input type="email" name="email" required autocomplete="email" placeholder="name@beispiel.ch"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-sm font-medium text-slate-700">Passwort</span>
                    <input type="password" name="password" required minlength="6" autocomplete="new-password" placeholder="Mindestens 6 Zeichen"
                           class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                </label>
                <label class="block">
                    <span class="mb-1.5 block text-sm font-medium text-slate-700">Rolle</span>
                    <select name="role"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                        <option value="0" selected>Lernender</option>
                        <option value="1">Fachkraft</option>
                        <option value="2">Administrator</option>
                    </select>
                </label>
                <button type="submit"
                        class="w-full rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    Konto erstellen
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function switchTab(tab) {
        document.querySelectorAll('.auth-tab').forEach(function (b) {
            var on = b.getAttribute('data-tab') === tab;
            b.classList.toggle('bg-white', on);
            b.classList.toggle('text-ink', on);
            b.classList.toggle('shadow-sm', on);
            b.classList.toggle('text-slate-500', !on);
        });
        document.querySelectorAll('.auth-panel').forEach(function (p) {
            p.style.display = p.getAttribute('data-panel') === tab ? 'block' : 'none';
        });
    }
    switchTab(<?= json_encode($authMode) ?>);
</script>
</body>
</html>
