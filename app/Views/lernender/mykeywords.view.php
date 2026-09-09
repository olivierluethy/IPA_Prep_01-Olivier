<?php
$page = ['active' => 'keywords', 'title' => 'Keywords', 'icon' => 'fa-tags'];
require __DIR__ . '/../general/head.php';
?>

<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-slate-500">Verwalte deine Themen, um Berichte schnell zuzuordnen.</p>
    <a href="addkeyword"
       class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
        <i class="fa-solid fa-plus"></i> Neues Keyword
    </a>
</div>

<?php if (empty($arrayKeywords)): ?>
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
            <i class="fa-solid fa-tags text-lg"></i>
        </span>
        <h3 class="mt-4 font-medium text-ink">Noch keine Keywords</h3>
        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">
            Lege dein erstes Keyword an, um deine Tagesberichte thematisch zu ordnen.
        </p>
        <a href="addkeyword"
           class="mt-5 inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-700">
            <i class="fa-solid fa-plus"></i> Erstes Keyword anlegen
        </a>
    </div>
<?php else: ?>
    <ul class="grid grid-cols-1 gap-3 sm:grid-cols-2">
        <?php foreach ($arrayKeywords as $k): ?>
            <li class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-card">
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                    <i class="fa-solid fa-tag"></i>
                </span>
                <p class="min-w-0 flex-1 truncate font-medium text-ink"><?= e($k['thema']) ?></p>
                <div class="flex shrink-0 items-center gap-1">
                    <a href="editKeyword?id=<?= (int) $k['themaId'] ?>"
                       class="inline-flex items-center justify-center rounded-lg p-2 text-slate-400 transition hover:bg-slate-100 hover:text-brand-600" title="Bearbeiten">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <a href="deleteKeyword?id=<?= (int) $k['themaId'] ?>"
                       onclick="return confirm('Dieses Keyword endgültig löschen?')"
                       class="inline-flex items-center justify-center rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600" title="Löschen">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<?php require __DIR__ . '/../general/foot.php'; ?>
