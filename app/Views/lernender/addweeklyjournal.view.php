<?php
$page = ['active' => 'weeklyraport', 'title' => 'Neuer Wochenbericht', 'icon' => 'fa-calendar-week'];
require __DIR__ . '/../general/head.php';
?>

<a href="weeklyraport" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-brand-600">
    <i class="fa-solid fa-arrow-left"></i> Zurück zu den Wochenberichten
</a>

<form action="addweeklyjournal" method="POST" class="space-y-6">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <label for="calendar_week" class="mb-1.5 block text-sm font-medium text-slate-700">Kalenderwoche</label>
        <input type="number" name="calendar_week" id="calendar_week" min="1" max="53" required
               placeholder="z. B. 34"
               class="w-full max-w-[10rem] rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
        <p class="mt-2 text-xs text-slate-400">Die Kalenderwoche, die dieser Bericht abdeckt (1–53).</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <label for="editor_completed_tasks" class="mb-2 block text-sm font-medium text-slate-700">Erledigte Arbeiten</label>
        <textarea name="completed_tasks" id="editor_completed_tasks" rows="8"></textarea>
        <p class="mt-2 text-xs text-slate-400">Was hast du diese Woche abgeschlossen?</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <label for="editor_still_in_work" class="mb-2 block text-sm font-medium text-slate-700">Laufende Arbeiten</label>
        <textarea name="still_in_work" id="editor_still_in_work" rows="8"></textarea>
        <p class="mt-2 text-xs text-slate-400">Woran arbeitest du aktuell noch?</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <label for="editor_reflection" class="mb-2 block text-sm font-medium text-slate-700">Reflexion</label>
        <textarea name="reflection" id="editor_reflection" rows="8"></textarea>
        <p class="mt-2 text-xs text-slate-400">Was hast du gelernt? Was ist dir gut gelungen?</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <label for="editor_issues" class="mb-2 block text-sm font-medium text-slate-700">Aufgetretene Probleme</label>
        <textarea name="issues" id="editor_issues" rows="8"></textarea>
        <p class="mt-2 text-xs text-slate-400">Welche Schwierigkeiten sind aufgetreten und wie bist du damit umgegangen?</p>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="weeklyraport" class="rounded-lg px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100">Abbrechen</a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
            <i class="fa-solid fa-floppy-disk"></i> Als Entwurf speichern
        </button>
    </div>
</form>

<script src="ckeditor/ckeditor.js"></script>
<script>
    if (window.CKEDITOR) {
        CKEDITOR.replace('editor_completed_tasks', { height: 240, removePlugins: 'elementspath', resize_enabled: false });
        CKEDITOR.replace('editor_still_in_work',   { height: 240, removePlugins: 'elementspath', resize_enabled: false });
        CKEDITOR.replace('editor_reflection',      { height: 240, removePlugins: 'elementspath', resize_enabled: false });
        CKEDITOR.replace('editor_issues',          { height: 240, removePlugins: 'elementspath', resize_enabled: false });
    }
</script>

<?php require __DIR__ . '/../general/foot.php'; ?>
