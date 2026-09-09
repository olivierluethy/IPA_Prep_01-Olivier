<?php
$page = ['active' => '', 'title' => 'Suche', 'icon' => 'fa-magnifying-glass'];
require __DIR__ . '/head.php';
?>

<!-- Suchformular -->
<form action="search" method="get" class="mb-8" role="search">
    <div class="relative">
        <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
        <input type="search" name="q" value="<?= e($q) ?>" autofocus
               placeholder="<?= e($role === 0 ? 'Einträge oder Keywords suchen…' : 'Berichte oder Lernende suchen…') ?>"
               class="w-full rounded-lg border border-slate-300 py-3 pl-11 pr-28 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
        <button type="submit"
                class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
            Suchen
        </button>
    </div>
</form>

<?php if ($q === ''): ?>

    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
            <i class="fa-solid fa-magnifying-glass text-lg"></i>
        </span>
        <h3 class="mt-4 font-medium text-ink">Gib einen Suchbegriff ein…</h3>
        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
            <?= $role === 0
                ? 'Durchsuche deine Tagesberichte, Wochenberichte und Keywords.'
                : 'Durchsuche freigegebene Berichte deiner Lernenden nach Inhalt oder Name.' ?>
        </p>
    </div>

<?php elseif ($total === 0): ?>

    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
            <i class="fa-solid fa-face-frown text-lg"></i>
        </span>
        <h3 class="mt-4 font-medium text-ink">Keine Treffer für „<?= e($q) ?>“</h3>
        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Versuche es mit einem anderen Suchbegriff.</p>
    </div>

<?php else: ?>

    <p class="mb-6 text-sm text-slate-500">
        <?= (int) $total ?> <?= $total === 1 ? 'Treffer' : 'Treffer' ?> für „<?= e($q) ?>“
    </p>

    <div class="space-y-8">

        <!-- Tagesberichte -->
        <?php if (!empty($daily)): ?>
            <section>
                <div class="mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-day text-brand-600"></i>
                    <h2 class="font-serif text-lg font-semibold text-ink">Tagesberichte</h2>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500"><?= count($daily) ?></span>
                </div>
                <ul class="divide-y divide-slate-100 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                    <?php foreach ($daily as $row):
                        $url = $role === 0
                            ? 'editDailyReport?id=' . (int) $row['journalId']
                            : 'seeDaily?id=' . (int) $row['journalId'];
                    ?>
                        <li>
                            <a href="<?= e($url) ?>" class="flex items-center gap-4 px-4 py-4 transition hover:bg-slate-50 sm:px-6">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <?php if (isset($row['full_name'])): ?>
                                            <span class="text-sm font-medium text-ink"><?= e($row['full_name']) ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($row['status'])): ?>
                                            <?= statusBadge((int) $row['status']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mt-0.5 truncate text-sm text-slate-500"><?= e(excerpt($row['text'], 120)) ?: '<span class="italic">Kein Text</span>' ?></p>
                                </div>
                                <span class="hidden shrink-0 text-xs text-slate-400 sm:block"><?= e(formatDate($row['datum'])) ?></span>
                                <i class="fa-solid fa-chevron-right shrink-0 text-slate-300"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <!-- Wochenberichte -->
        <?php if (!empty($weekly)): ?>
            <section>
                <div class="mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-calendar-week text-indigo-600"></i>
                    <h2 class="font-serif text-lg font-semibold text-ink">Wochenberichte</h2>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500"><?= count($weekly) ?></span>
                </div>
                <ul class="divide-y divide-slate-100 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
                    <?php foreach ($weekly as $row):
                        $url = $role === 0
                            ? 'editWeeklyRaport?id=' . (int) $row['wochenreportId']
                            : 'seeWeekly?id=' . (int) $row['wochenreportId'];
                    ?>
                        <li>
                            <a href="<?= e($url) ?>" class="flex items-center gap-4 px-4 py-4 transition hover:bg-slate-50 sm:px-6">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-sm font-medium text-ink">KW <?= (int) $row['kalenderwoche'] ?></span>
                                        <?php if (isset($row['full_name'])): ?>
                                            <span class="text-sm text-slate-500"><?= e($row['full_name']) ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($row['status'])): ?>
                                            <?= statusBadge((int) $row['status']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mt-0.5 truncate text-sm text-slate-500"><?= e(excerpt($row['erledigte_arbeiten'], 120)) ?: '<span class="italic">Kein Text</span>' ?></p>
                                </div>
                                <span class="hidden shrink-0 text-xs text-slate-400 sm:block"><?= e(formatDate($row['datum'])) ?></span>
                                <i class="fa-solid fa-chevron-right shrink-0 text-slate-300"></i>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <!-- Keywords (nur Lernende) -->
        <?php if (!empty($keywords)): ?>
            <section>
                <div class="mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-tags text-emerald-600"></i>
                    <h2 class="font-serif text-lg font-semibold text-ink">Keywords</h2>
                    <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500"><?= count($keywords) ?></span>
                </div>
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($keywords as $row): ?>
                        <a href="editKeyword?id=<?= (int) $row['themaId'] ?>"
                           class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-600 transition hover:border-brand-300 hover:text-brand-700">
                            <i class="fa-solid fa-tag text-xs text-slate-400"></i> <?= e($row['thema']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

    </div>

<?php endif; ?>

<?php require __DIR__ . '/foot.php'; ?>
