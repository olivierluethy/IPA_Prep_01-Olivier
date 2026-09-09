<?php
/**
 * Gemeinsame App-Shell (Kopf) für alle eingeloggten Seiten.
 *
 * Vor dem Einbinden setzen:
 *   $page = [
 *     'active'  => 'home',          // Schlüssel des aktiven Nav-Eintrags
 *     'title'   => 'Dashboard',     // Seitentitel im Header
 *     'icon'    => 'fa-gauge-high', // Font-Awesome-Icon zum Titel
 *   ];
 *
 * Danach: require __DIR__ . '/../general/head.php';  (bzw. '/general/head.php')
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$page      = $page ?? [];
$active    = $page['active'] ?? '';
$pageTitle = $page['title']  ?? 'Lernjournal';
$pageIcon  = $page['icon']   ?? 'fa-book';

$role      = (int) ($_SESSION['role'] ?? 0);
$fullName  = $_SESSION['full_name'] ?? 'Benutzer';
$email     = $_SESSION['email'] ?? '';
$avatar    = $_SESSION['profileImageUrl'] ?? avatarDataUri($fullName);

$roleLabel = ['Lernender', 'Fachkraft', 'Administrator'][$role] ?? 'Benutzer';

/* Rollenbasierte Navigation */
$nav = [];
if ($role === 0) {
    $nav = [
        ['home',        'Dashboard',      'fa-gauge-high'],
        ['dailyraport', 'Tagesberichte',  'fa-calendar-day'],
        ['weeklyraport','Wochenberichte', 'fa-calendar-week'],
        ['keywords',    'Keywords',       'fa-tags'],
    ];
} elseif ($role === 1) {
    $nav = [
        ['home',     'Dashboard', 'fa-gauge-high'],
        ['overview', 'Übersicht', 'fa-layer-group'],
    ];
} else {
    $nav = [
        ['home',         'Dashboard', 'fa-gauge-high'],
        ['useroverview', 'Benutzer',  'fa-users-gear'],
    ];
}

$searchPlaceholder = $role === 1 ? 'Berichte oder Lernende suchen…' : 'Einträge oder Keywords suchen…';
?>
<!DOCTYPE html>
<html lang="de" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lernjournal · <?= e($pageTitle) ?></title>
    <link rel="shortcut icon" href="images/favicon.ico">

    <link rel="stylesheet" href="public/fontawesome/css/all.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Source+Serif+4:opsz,wght@8..60,500;8..60,600;8..60,700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50:  '#eef2ff', 100: '#e0e7ff', 200: '#c7d2fe',
                            500: '#6366f1', 600: '#4f46e5', 700: '#4338ca',
                            900: '#312e81', 950: '#1e1b4b',
                        },
                        ink: '#0f172a',
                    },
                    fontFamily: {
                        sans:  ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                        serif: ['"Source Serif 4"', 'Georgia', 'serif'],
                    },
                    boxShadow: {
                        card: '0 1px 2px 0 rgb(15 23 42 / 0.04), 0 1px 3px 0 rgb(15 23 42 / 0.06)',
                    },
                },
            },
        };
    </script>
    <style>
        [x-cloak] { display: none !important; }
        /* Rich-Text-Ausgabe (CKEditor-Inhalte) */
        .prose-journal { color: #334155; line-height: 1.7; }
        .prose-journal p { margin: 0 0 .75rem; }
        .prose-journal ul { list-style: disc; padding-left: 1.25rem; margin: 0 0 .75rem; }
        .prose-journal ol { list-style: decimal; padding-left: 1.25rem; margin: 0 0 .75rem; }
        .prose-journal a  { color: #4f46e5; text-decoration: underline; }
        .prose-journal h1, .prose-journal h2, .prose-journal h3 { font-weight: 600; color: #0f172a; margin: 1rem 0 .5rem; }
        /* CKEditor an das neue Design angleichen */
        .cke_chrome { border: 1px solid #e2e8f0 !important; border-radius: 12px !important; overflow: hidden; }
        .cke_top { background: #f8fafc !important; border-bottom: 1px solid #e2e8f0 !important; }
    </style>
</head>

<body class="h-full bg-slate-50 font-sans text-ink antialiased">

<div class="min-h-full">

    <!-- Mobile-Overlay -->
    <div id="navOverlay" class="fixed inset-0 z-30 hidden bg-slate-900/50 lg:hidden" onclick="toggleNav(false)"></div>

    <!-- Sidebar -->
    <aside id="sidebar"
           class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-brand-950 text-slate-300 transition-transform duration-200 lg:translate-x-0">
        <div class="flex h-16 items-center gap-3 px-6">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-600 text-white">
                <i class="fa-solid fa-book-open"></i>
            </span>
            <span class="font-serif text-lg font-semibold text-white">Lernjournal</span>
        </div>

        <nav class="mt-2 flex-1 space-y-1 px-3">
            <?php foreach ($nav as [$key, $label, $icon]): ?>
                <?php $isActive = $active === $key; ?>
                <a href="<?= e($key) ?>"
                   class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                          <?= $isActive
                                ? 'bg-brand-600 text-white shadow-sm'
                                : 'text-slate-300 hover:bg-white/5 hover:text-white' ?>">
                    <i class="fa-solid <?= e($icon) ?> w-5 text-center <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-white' ?>"></i>
                    <span><?= e($label) ?></span>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="border-t border-white/10 p-3">
            <a href="profile"
               class="flex items-center gap-3 rounded-lg px-3 py-2 transition hover:bg-white/5">
                <img src="<?= e($avatar) ?>" alt="" class="h-9 w-9 rounded-full object-cover ring-2 ring-white/10">
                <span class="min-w-0 flex-1">
                    <span class="block truncate text-sm font-medium text-white"><?= e($fullName) ?></span>
                    <span class="block truncate text-xs text-slate-400"><?= e($roleLabel) ?></span>
                </span>
            </a>
        </div>
    </aside>

    <!-- Hauptbereich -->
    <div class="lg:pl-64">

        <!-- Header -->
        <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/90 backdrop-blur">
            <div class="flex h-16 items-center gap-3 px-4 sm:px-6">
                <button type="button" onclick="toggleNav(true)"
                        class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 lg:hidden" aria-label="Menü öffnen">
                    <i class="fa-solid fa-bars text-lg"></i>
                </button>

                <!-- Suche -->
                <form action="search" method="get" class="relative hidden flex-1 sm:block" role="search">
                    <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="search" name="q"
                           value="<?= e($_GET['q'] ?? '') ?>"
                           placeholder="<?= e($searchPlaceholder) ?>"
                           class="w-full max-w-md rounded-lg border border-slate-200 bg-slate-50 py-2 pl-10 pr-4 text-sm text-ink placeholder:text-slate-400 focus:border-brand-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-brand-100">
                </form>

                <div class="flex flex-1 items-center justify-end gap-2 sm:flex-none">
                    <!-- Suche (mobil) -->
                    <a href="search" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100 sm:hidden" aria-label="Suchen">
                        <i class="fa-solid fa-magnifying-glass text-lg"></i>
                    </a>

                    <!-- Profil-Menü -->
                    <div class="relative" id="profileMenu">
                        <button type="button" onclick="toggleProfileMenu()"
                                class="flex items-center gap-2 rounded-lg p-1.5 pr-2 hover:bg-slate-100">
                            <img src="<?= e($avatar) ?>" alt="" class="h-8 w-8 rounded-full object-cover ring-1 ring-slate-200">
                            <span class="hidden text-sm font-medium text-ink md:block"><?= e($fullName) ?></span>
                            <i class="fa-solid fa-chevron-down text-xs text-slate-400"></i>
                        </button>
                        <div id="profileDropdown"
                             class="absolute right-0 mt-2 hidden w-56 rounded-xl border border-slate-200 bg-white py-1.5 shadow-lg">
                            <div class="border-b border-slate-100 px-4 py-3">
                                <p class="truncate text-sm font-semibold text-ink"><?= e($fullName) ?></p>
                                <p class="truncate text-xs text-slate-500"><?= e($email) ?></p>
                            </div>
                            <a href="profile" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50">
                                <i class="fa-solid fa-user w-4 text-slate-400"></i> Profil &amp; Einstellungen
                            </a>
                            <a href="logout" class="flex items-center gap-3 px-4 py-2.5 text-sm text-rose-600 hover:bg-rose-50">
                                <i class="fa-solid fa-right-from-bracket w-4"></i> Abmelden
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Seiteninhalt -->
        <main class="px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-5xl">
                <div class="mb-8 flex items-center gap-3">
                    <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-brand-50 text-brand-600">
                        <i class="fa-solid <?= e($pageIcon) ?>"></i>
                    </span>
                    <h1 class="font-serif text-2xl font-semibold text-ink sm:text-3xl"><?= e($pageTitle) ?></h1>
                </div>

                <?= renderFlash() ?>
