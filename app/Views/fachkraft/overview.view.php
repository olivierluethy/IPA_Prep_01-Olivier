<?php
$page = ['active' => 'overview', 'title' => 'Übersicht', 'icon' => 'fa-layer-group'];
require __DIR__ . '/../general/head.php';

$showDaily  = ($type === 'all' || $type === 'daily');
$showWeekly = ($type === 'all' || $type === 'weekly');
?>

<p class="mb-6 text-sm text-slate-500">Alle freigegebenen Berichte deiner Lernenden auf einen Blick.</p>

<!-- Filter -->
<form action="overview" method="get" class="mb-8 rounded-2xl border border-slate-200 bg-white p-4 shadow-card sm:p-5">
    <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label for="q" class="mb-1.5 block text-sm font-medium text-slate-700">Suche</label>
            <div class="relative">
                <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="search" id="q" name="q" value="<?= e($q) ?>"
                       placeholder="Nach Name oder Inhalt suchen…"
                       class="w-full rounded-lg border border-slate-300 py-2.5 pl-10 pr-3 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>
        </div>
        <div class="sm:w-56">
            <label for="type" class="mb-1.5 block text-sm font-medium text-slate-700">Berichtstyp</label>
            <select id="type" name="type"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                <option value="all"    <?= $type === 'all'    ? 'selected' : '' ?>>Alle Berichte</option>
                <option value="daily"  <?= $type === 'daily'  ? 'selected' : '' ?>>Tagesberichte</option>
                <option value="weekly" <?= $type === 'weekly' ? 'selected' : '' ?>>Wochenberichte</option>
            </select>
        </div>
        <button type="submit"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
            <i class="fa-solid fa-filter"></i> Filtern
        </button>
    </div>
</form>

<?php if ($showDaily): ?>
<!-- Tagesberichte -->
<section class="mb-8">
    <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-400">
        <i class="fa-solid fa-calendar-day"></i> Tagesberichte
        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500"><?= count($arrayDailyRaports) ?></span>
    </h2>

    <?php if (empty($arrayDailyRaports)): ?>
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500">
            Keine Berichte gefunden.
        </div>
    <?php else: ?>
        <ul class="divide-y divide-slate-100 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-card">
            <?php foreach ($arrayDailyRaports as $r): ?>
                <li class="flex items-center gap-4 px-4 py-4 transition hover:bg-slate-50 sm:px-5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                        <i class="fa-solid fa-calendar-day"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <p class="truncate font-medium text-ink"><?= e($r['full_name'] ?? '') ?></p>
                            <span class="shrink-0 text-xs text-slate-400"><?= e(formatDate($r['datum'] ?? '')) ?></span>
                        </div>
                        <p class="mt-0.5 truncate text-sm text-slate-600"><?= e(excerpt((string) ($r['text'] ?? ''), 120)) ?: '<span class="italic text-slate-400">Kein Text</span>' ?></p>
                    </div>
                    <a href="seeDaily?id=<?= (int) $r['journalId'] ?>"
                       class="inline-flex shrink-0 items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-brand-600 transition hover:bg-brand-50">
                        Ansehen <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
<?php endif; ?>

<?php if ($showWeekly): ?>
<!-- Wochenberichte -->
<section>
    <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-400">
        <i class="fa-solid fa-calendar-week"></i> Wochenberichte
        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500"><?= count($arrayWeeklyRaports) ?></span>
    </h2>

    <?php if (empty($arrayWeeklyRaports)): ?>
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500">
            Keine Berichte gefunden.
        </div>
    <?php else: ?>
        <ul class="divide-y divide-slate-100 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-card">
            <?php foreach ($arrayWeeklyRaports as $r): ?>
                <li class="flex items-center gap-4 px-4 py-4 transition hover:bg-slate-50 sm:px-5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                        <i class="fa-solid fa-calendar-week"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <p class="truncate font-medium text-ink"><?= e($r['full_name'] ?? '') ?></p>
                            <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-600">KW <?= (int) ($r['kalenderwoche'] ?? 0) ?></span>
                            <span class="shrink-0 text-xs text-slate-400"><?= e(formatDate($r['datum'] ?? '')) ?></span>
                        </div>
                        <?php if (!empty($r['reflexion'])): ?>
                            <p class="mt-0.5 truncate text-sm text-slate-600"><?= e(excerpt((string) $r['reflexion'], 120)) ?></p>
                        <?php endif; ?>
                    </div>
                    <a href="seeWeekly?id=<?= (int) $r['wochenreportId'] ?>"
                       class="inline-flex shrink-0 items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-brand-600 transition hover:bg-brand-50">
                        Ansehen <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
<?php endif; ?>

<?php require __DIR__ . '/../general/foot.php'; ?>
