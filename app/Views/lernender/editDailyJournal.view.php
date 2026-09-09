<?php
$page = ['active' => 'dailyraport', 'title' => 'Tagesbericht bearbeiten', 'icon' => 'fa-calendar-day'];
require __DIR__ . '/../general/head.php';

$report      = $getDailyReport[0];
$pickedIds   = array_column($getPickedKeywords ?? [], 'themaId');
?>

<a href="dailyraport" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-brand-600">
    <i class="fa-solid fa-arrow-left"></i> Zurück zu den Tagesberichten
</a>

<form action="editDailyReport?id=<?= (int) $report['journalId'] ?>" method="POST" class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <label for="editor_text" class="mb-2 block text-sm font-medium text-slate-700">
            Woran hast du gearbeitet?
        </label>
        <textarea name="text" id="editor_text" rows="10"><?= richtext($report['text']) ?></textarea>
        <p class="mt-2 text-xs text-slate-400">Beschreibe deine Tätigkeiten, was du gelernt hast und offene Punkte.</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <h2 class="mb-1 text-sm font-medium text-slate-700">Keywords zuordnen</h2>
        <p class="mb-4 text-xs text-slate-400">Ordne den Bericht deinen Themen zu (optional).</p>

        <?php if (empty($getKeywords)): ?>
            <div class="rounded-xl border border-dashed border-slate-300 p-5 text-center text-sm text-slate-500">
                Du hast noch keine Keywords.
                <a href="addkeyword" class="font-medium text-brand-600 hover:text-brand-700">Jetzt eines anlegen</a>.
            </div>
        <?php else: ?>
            <div class="flex flex-wrap gap-2">
                <?php foreach ($getKeywords as $topic): ?>
                    <?php $checked = in_array($topic['themaId'], $pickedIds); ?>
                    <label class="cursor-pointer">
                        <input type="checkbox" name="topics[]" value="<?= (int) $topic['themaId'] ?>" class="peer sr-only"<?= $checked ? ' checked' : '' ?>>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-200 bg-white px-3 py-1.5 text-sm text-slate-600 transition peer-checked:border-brand-600 peer-checked:bg-brand-600 peer-checked:text-white hover:border-brand-300">
                            <i class="fa-solid fa-tag text-xs"></i> <?= e($topic['thema']) ?>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="dailyraport" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Abbrechen</a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
            <i class="fa-solid fa-floppy-disk"></i> Änderungen speichern
        </button>
    </div>
</form>

<script src="ckeditor/ckeditor.js"></script>
<script>
    if (window.CKEDITOR) {
        CKEDITOR.replace('editor_text', { height: 240, removePlugins: 'elementspath', resize_enabled: false });
    }
</script>

<?php require __DIR__ . '/../general/foot.php'; ?>
