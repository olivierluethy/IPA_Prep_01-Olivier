<?php
$page = ['active' => 'overview', 'title' => 'Wochenbericht', 'icon' => 'fa-calendar-week'];
require __DIR__ . '/../general/head.php';

$week = $weekArray[0] ?? null;
?>

<a href="overview" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-brand-600">
    <i class="fa-solid fa-arrow-left"></i> Zurück zur Übersicht
</a>

<?php if (!$week): ?>
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-indigo-50 text-indigo-600">
            <i class="fa-solid fa-calendar-week text-lg"></i>
        </span>
        <h3 class="mt-4 font-medium text-ink">Wochenbericht nicht gefunden</h3>
        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Dieser Bericht existiert nicht oder wurde entfernt.</p>
    </div>
<?php else: ?>
    <!-- Kopf -->
    <div class="mb-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <div class="flex flex-wrap items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600">
                <i class="fa-solid fa-calendar-week"></i>
            </span>
            <div class="min-w-0 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <p class="font-medium text-ink"><?= e($week['full_name'] ?? '') ?></p>
                    <span class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-50 px-2 py-0.5 text-xs font-semibold text-indigo-600">KW <?= (int) ($week['kalenderwoche'] ?? 0) ?></span>
                </div>
                <p class="text-sm text-slate-400"><?= e(formatDate($week['datum'] ?? '')) ?></p>
            </div>
            <?= statusBadge((int) ($week['status'] ?? 0)) ?>
        </div>
    </div>

    <?php
    $sections = [
        ['Erledigte Arbeiten',    'fa-list-check',        $week['erledigte_arbeiten']   ?? ''],
        ['Laufende Arbeiten',     'fa-spinner',           $week['laufende_arbeiten']    ?? ''],
        ['Reflexion',             'fa-lightbulb',         $week['reflexion']            ?? ''],
        ['Aufgetretene Probleme', 'fa-triangle-exclamation', $week['aufgetretene_probleme'] ?? ''],
    ];
    ?>
    <div class="space-y-6">
        <?php foreach ($sections as [$label, $icon, $content]): ?>
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
                <h2 class="mb-3 flex items-center gap-2 text-sm font-semibold uppercase tracking-wide text-slate-400">
                    <i class="fa-solid <?= $icon ?>"></i> <?= e($label) ?>
                </h2>
                <?php if (trim(strip_tags((string) $content)) === ''): ?>
                    <p class="text-sm italic text-slate-400">Keine Angaben.</p>
                <?php else: ?>
                    <div class="prose-journal"><?= richtext($content) ?></div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../general/foot.php'; ?>
