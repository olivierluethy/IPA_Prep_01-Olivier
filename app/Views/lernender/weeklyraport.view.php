<?php
$page = ['active' => 'weeklyraport', 'title' => 'Wochenberichte', 'icon' => 'fa-calendar-week'];
require __DIR__ . '/../general/head.php';
?>

<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-slate-500">Fasse deine Arbeitswoche zusammen und reflektiere.</p>
    <a href="addweeklyjournal"
       class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
        <i class="fa-solid fa-plus"></i> Neuer Wochenbericht
    </a>
</div>

<!-- Entwürfe -->
<section class="mb-8">
    <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-400">
        <i class="fa-solid fa-pen-ruler"></i> Entwürfe
        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500"><?= count($arrayWeeklyInProcess) ?></span>
    </h2>

    <?php if (empty($arrayWeeklyInProcess)): ?>
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500">
            Keine Entwürfe offen. <a href="addweeklyjournal" class="font-medium text-brand-600 hover:text-brand-700">Neuen Wochenbericht schreiben</a>.
        </div>
    <?php else: ?>
        <ul class="space-y-3">
            <?php foreach ($arrayWeeklyInProcess as $r): ?>
                <li class="rounded-xl border border-slate-200 bg-white p-4 shadow-card sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-2.5 py-1 text-sm font-semibold text-indigo-600">
                                    <i class="fa-solid fa-calendar-week text-xs"></i> KW <?= (int) $r['kalenderwoche'] ?>
                                </span>
                                <span class="text-xs font-medium text-slate-400"><?= e(formatDate($r['datum'])) ?></span>
                                <?= statusBadge(0) ?>
                            </div>
                            <p class="mt-1.5 text-sm text-slate-600"><?= e(excerpt($r['erledigte_arbeiten'], 180)) ?: '<span class="italic text-slate-400">Kein Text</span>' ?></p>
                        </div>
                        <div class="flex shrink-0 items-center gap-1">
                            <a href="editWeeklyRaport?id=<?= (int) $r['wochenreportId'] ?>"
                               class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                                <i class="fa-solid fa-pen"></i> Bearbeiten
                            </a>
                            <a href="releaseWeeklyReport?id=<?= (int) $r['wochenreportId'] ?>"
                               onclick="return confirm('Diesen Wochenbericht an die Fachkraft freigeben?')"
                               class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-emerald-600 transition hover:bg-emerald-50">
                                <i class="fa-solid fa-paper-plane"></i> Freigeben
                            </a>
                            <a href="deleteWeeklyRaport?id=<?= (int) $r['wochenreportId'] ?>"
                               onclick="return confirm('Diesen Wochenbericht endgültig löschen?')"
                               class="inline-flex items-center justify-center rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600" title="Löschen">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<!-- Freigegeben -->
<section>
    <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-400">
        <i class="fa-solid fa-circle-check"></i> Freigegeben
        <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-500"><?= count($arrayWeeklyIsReleased) ?></span>
    </h2>

    <?php if (empty($arrayWeeklyIsReleased)): ?>
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500">
            Noch nichts freigegeben. Gib einen Entwurf frei, damit ihn deine Fachkraft sieht.
        </div>
    <?php else: ?>
        <ul class="divide-y divide-slate-100 overflow-hidden rounded-xl border border-slate-200 bg-white shadow-card">
            <?php foreach ($arrayWeeklyIsReleased as $r): ?>
                <li class="flex items-center gap-4 px-4 py-4 sm:px-5">
                    <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <i class="fa-solid fa-circle-check"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-semibold text-ink">KW <?= (int) $r['kalenderwoche'] ?></span>
                        </div>
                        <p class="mt-0.5 truncate text-sm text-slate-600"><?= e(excerpt($r['erledigte_arbeiten'], 120)) ?: '<span class="italic text-slate-400">Kein Text</span>' ?></p>
                    </div>
                    <a href="seeWeekly?id=<?= (int) $r['wochenreportId'] ?>"
                       class="shrink-0 rounded-lg px-3 py-2 text-sm font-medium text-brand-600 transition hover:bg-brand-50">
                        Ansehen
                    </a>
                    <span class="hidden shrink-0 text-xs text-slate-400 sm:block"><?= e(formatDate($r['datum'])) ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../general/foot.php'; ?>
