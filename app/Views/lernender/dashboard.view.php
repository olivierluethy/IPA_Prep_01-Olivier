<?php
$page = ['active' => 'home', 'title' => 'Dashboard', 'icon' => 'fa-gauge-high'];
require __DIR__ . '/../general/head.php';

$firstName = $_SESSION['first_name'] ?? '';
?>

<!-- Begrüßung + Schnellaktionen -->
<section class="mb-8 overflow-hidden rounded-2xl bg-brand-950 p-6 text-white sm:p-8">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-brand-200">Hallo <?= e($firstName) ?>, schön dass du da bist.</p>
            <h2 class="mt-1 font-serif text-2xl font-semibold">Was möchtest du heute festhalten?</h2>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="adddailyjournal"
               class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500">
                <i class="fa-solid fa-plus"></i> Neuer Tagesbericht
            </a>
            <a href="addweeklyjournal"
               class="inline-flex items-center gap-2 rounded-lg bg-white/10 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/20 transition hover:bg-white/20">
                <i class="fa-solid fa-calendar-week"></i> Neuer Wochenbericht
            </a>
        </div>
    </div>
</section>

<!-- Kennzahlen -->
<section class="mb-8 grid grid-cols-2 gap-4 lg:grid-cols-4">
    <?php
    $tiles = [
        ['fa-calendar-day',  'text-brand-600 bg-brand-50',   $stats['dailyTotal'],  'Tagesberichte'],
        ['fa-calendar-week', 'text-indigo-600 bg-indigo-50', $stats['weeklyTotal'], 'Wochenberichte'],
        ['fa-pen-ruler',     'text-amber-600 bg-amber-50',   $stats['drafts'],      'Entwürfe'],
        ['fa-tags',          'text-emerald-600 bg-emerald-50',$stats['keywords'],   'Keywords'],
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

<!-- Letzte Einträge -->
<section>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="font-serif text-xl font-semibold text-ink">Deine letzten Einträge</h2>
        <a href="dailyraport" class="text-sm font-medium text-brand-600 hover:text-brand-700">Alle Tagesberichte</a>
    </div>

    <?php if (empty($recent)): ?>
        <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                <i class="fa-solid fa-feather-pointed text-lg"></i>
            </span>
            <h3 class="mt-4 font-medium text-ink">Noch keine Einträge</h3>
            <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
                Dokumentiere deinen ersten Arbeitstag – es dauert nur eine Minute.
            </p>
            <a href="adddailyjournal"
               class="mt-5 inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
                <i class="fa-solid fa-plus"></i> Ersten Tagesbericht schreiben
            </a>
        </div>
    <?php else: ?>
        <ul class="divide-y divide-slate-100 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
            <?php foreach (array_slice($recent, 0, 8) as $item):
                $isDaily = $item['type'] === 'daily';
                $editUrl = $isDaily ? 'editDailyReport?id=' . (int) $item['id'] : 'editWeeklyRaport?id=' . (int) $item['id'];
            ?>
                <li class="flex items-center gap-4 px-4 py-4 transition hover:bg-slate-50 sm:px-6">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg <?= $isDaily ? 'bg-brand-50 text-brand-600' : 'bg-indigo-50 text-indigo-600' ?>">
                        <i class="fa-solid <?= $isDaily ? 'fa-calendar-day' : 'fa-calendar-week' ?>"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <p class="truncate font-medium text-ink"><?= e($item['title']) ?></p>
                            <?= statusBadge((int) $item['status']) ?>
                        </div>
                        <p class="mt-0.5 truncate text-sm text-slate-500"><?= e(excerpt($item['text'], 90)) ?: '<span class="italic">Kein Text</span>' ?></p>
                    </div>
                    <div class="hidden shrink-0 text-right sm:block">
                        <p class="text-xs text-slate-400"><?= e(formatDate($item['datum'])) ?></p>
                    </div>
                    <a href="<?= e($editUrl) ?>" class="shrink-0 rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-brand-600" title="Öffnen">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../general/foot.php'; ?>
