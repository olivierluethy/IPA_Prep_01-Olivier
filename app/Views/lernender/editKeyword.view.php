<?php
$page = ['active' => 'keywords', 'title' => 'Keyword bearbeiten', 'icon' => 'fa-tags'];
require __DIR__ . '/../general/head.php';

$keyword = $getKeyword[0];
?>

<a href="keywords" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-brand-600">
    <i class="fa-solid fa-arrow-left"></i> Zurück zu den Keywords
</a>

<form action="editKeyword?id=<?= (int) $keyword['themaId'] ?>" method="POST" class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <label for="thema" class="mb-1.5 block text-sm font-medium text-slate-700">Keyword</label>
        <input type="text" name="thema" id="thema" required
               value="<?= e($keyword['thema']) ?>"
               placeholder="z. B. Kundengespräch"
               class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
        <p class="mt-2 text-xs text-slate-400">Ein kurzer, prägnanter Begriff, dem du Berichte zuordnen kannst.</p>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="keywords" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Abbrechen</a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
            <i class="fa-solid fa-floppy-disk"></i> Änderungen speichern
        </button>
    </div>
</form>

<?php require __DIR__ . '/../general/foot.php'; ?>
