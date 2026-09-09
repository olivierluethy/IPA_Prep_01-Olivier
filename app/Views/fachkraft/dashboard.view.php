<?php
$page = ['active' => 'home', 'title' => 'Dashboard', 'icon' => 'fa-gauge-high'];
require __DIR__ . '/../general/head.php';

$firstName = $_SESSION['first_name'] ?? '';

// Freigaben zusammenführen und nach Datum sortieren.
$feed = [];
foreach ($daily as $d) {
    $feed[] = [
        'type'  => 'daily',
        'id'    => (int) $d['journalId'],
        'name'  => $d['full_name'] ?? '',
        'datum' => $d['datum'] ?? '',
        'label' => excerpt((string) ($d['text'] ?? ''), 90),
        'icon'  => 'fa-calendar-day',
        'tint'  => 'bg-brand-50 text-brand-600',
        'url'   => 'seeDaily?id=' . (int) $d['journalId'],
    ];
}
foreach ($weekly as $w) {
    $feed[] = [
        'type'  => 'weekly',
        'id'    => (int) $w['wochenreportId'],
        'name'  => $w['full_name'] ?? '',
        'datum' => $w['datum'] ?? '',
        'label' => 'KW ' . (int) ($w['kalenderwoche'] ?? 0),
        'icon'  => 'fa-calendar-week',
        'tint'  => 'bg-indigo-50 text-indigo-600',
        'url'   => 'seeWeekly?id=' . (int) $w['wochenreportId'],
    ];
}
usort($feed, fn($a, $b) => strtotime((string) $b['datum']) <=> strtotime((string) $a['datum']));
?>

<!-- Begrüßung -->
<section class="mb-8 overflow-hidden rounded-2xl bg-brand-950 p-6 text-white sm:p-8">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-brand-200">Hallo <?= e($firstName) ?>, schön dass du da bist.</p>
            <h2 class="mt-1 font-serif text-2xl font-semibold">Das haben deine Lernenden freigegeben.</h2>
        </div>
        <a href="overview"
           class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500">
            <i class="fa-solid fa-layer-group"></i> Zur Übersicht
        </a>
    </div>
</section>

<!-- Kennzahlen -->
<section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <?php
    $tiles = [
        ['fa-users',         'text-emerald-600 bg-emerald-50', count($lernende), 'Lernende'],
        ['fa-calendar-day',  'text-brand-600 bg-brand-50',     count($daily),    'Tagesberichte'],
        ['fa-calendar-week', 'text-indigo-600 bg-indigo-50',   count($weekly),   'Wochenberichte'],
    ];
    foreach ($tiles as [$icon, $tint, $value, $label]): ?>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-card">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg <?= $tint ?>">
                <i class="fa-solid <?= $icon ?>"></i>
            </span>
            <p class="mt-3 text-2xl font-semibold text-ink"><?= (int) $value ?></p>
            <p class="text-sm text-slate-500"><?= e($label) ?></p>
        </div>
    <?php endforeach; ?>
</section>

<!-- Neueste Freigaben -->
<section>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="font-serif text-xl font-semibold text-ink">Neueste Freigaben</h2>
        <a href="overview" class="text-sm font-medium text-brand-600 hover:text-brand-700">Alle ansehen</a>
    </div>

    <?php if (empty($feed)): ?>
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                <i class="fa-solid fa-inbox text-lg"></i>
            </span>
            <h3 class="mt-4 font-medium text-ink">Noch keine Freigaben</h3>
            <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                Sobald deine Lernenden Berichte freigeben, erscheinen sie hier.
            </p>
        </div>
    <?php else: ?>
        <ul class="divide-y divide-slate-100 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
            <?php foreach (array_slice($feed, 0, 8) as $item): ?>
                <li class="flex items-center gap-4 px-4 py-4 transition hover:bg-slate-50 sm:px-6">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg <?= $item['tint'] ?>">
                        <i class="fa-solid <?= $item['icon'] ?>"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <p class="truncate font-medium text-ink"><?= e($item['name']) ?></p>
                            <span class="shrink-0 text-xs text-slate-400"><?= e(formatDate($item['datum'])) ?></span>
                        </div>
                        <p class="mt-0.5 truncate text-sm text-slate-500"><?= e($item['label']) ?: '<span class="italic">Kein Text</span>' ?></p>
                    </div>
                    <a href="<?= e($item['url']) ?>"
                       class="inline-flex shrink-0 items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-brand-600 transition hover:bg-brand-50">
                        Ansehen <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../general/foot.php'; ?>
