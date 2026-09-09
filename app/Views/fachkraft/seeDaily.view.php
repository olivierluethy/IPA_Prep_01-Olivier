<?php
$page = ['active' => 'overview', 'title' => 'Tagesbericht', 'icon' => 'fa-calendar-day'];
require __DIR__ . '/../general/head.php';

$day = $dayArray[0] ?? null;
?>

<a href="overview" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-brand-600">
    <i class="fa-solid fa-arrow-left"></i> Zurück zur Übersicht
</a>

<?php if (!$day): ?>
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
            <i class="fa-solid fa-calendar-day text-lg"></i>
        </span>
        <h3 class="mt-4 font-medium text-ink">Tagesbericht nicht gefunden</h3>
        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Dieser Bericht existiert nicht oder wurde entfernt.</p>
    </div>
<?php else: ?>
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <div class="mb-5 flex flex-wrap items-center gap-3 border-b border-slate-100 pb-5">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                <i class="fa-solid fa-calendar-day"></i>
            </span>
            <div class="min-w-0 flex-1">
                <p class="font-medium text-ink"><?= e($day['full_name'] ?? '') ?></p>
                <p class="text-sm text-slate-400"><?= e(formatDate($day['datum'] ?? '')) ?></p>
            </div>
            <?= statusBadge((int) ($day['status'] ?? 0)) ?>
        </div>

        <div class="prose-journal"><?= richtext($day['text'] ?? '') ?></div>

        <?php if (!empty($getPickedKeywords)): ?>
            <div class="mt-6 flex flex-wrap gap-2 border-t border-slate-100 pt-5">
                <?php foreach ($getPickedKeywords as $kw): ?>
                    <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">
                        <i class="fa-solid fa-tag text-[10px] text-slate-400"></i> <?= e($kw['thema'] ?? '') ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../general/foot.php'; ?>
