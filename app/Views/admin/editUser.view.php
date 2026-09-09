<?php
$page = ['active' => 'useroverview', 'title' => 'Benutzer bearbeiten', 'icon' => 'fa-user-pen'];
require __DIR__ . '/../general/head.php';

$user = $getUser[0];
?>

<a href="useroverview" class="mb-6 inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-brand-600">
    <i class="fa-solid fa-arrow-left"></i> Zurück zur Benutzerverwaltung
</a>

<div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
    <!-- Benutzerkopf -->
    <div class="mb-6 flex items-center gap-4 border-b border-slate-100 pb-6">
        <img src="<?= e(avatarDataUri($user['full_name'] ?? '')) ?>" alt="" class="h-14 w-14 shrink-0 rounded-full object-cover ring-1 ring-slate-200">
        <div class="min-w-0">
            <p class="font-medium text-ink"><?= e($user['full_name'] ?? '') ?></p>
            <p class="text-sm text-slate-500"><?= e($user['email'] ?? '') ?></p>
        </div>
    </div>

    <form action="editUser?id=<?= (int) $user['benutzerId'] ?>" method="POST">
        <div class="max-w-sm">
            <label for="role" class="mb-1.5 block text-sm font-medium text-slate-700">Rolle</label>
            <select id="role" name="role"
                    class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                <?php
                $roles = [0 => 'Lernender', 1 => 'Fachkraft', 2 => 'Administrator'];
                $current = (int) $user['role'];
                foreach ($roles as $value => $label): ?>
                    <option value="<?= $value ?>" <?= $current === $value ? 'selected' : '' ?>><?= e($label) ?></option>
                <?php endforeach; ?>
            </select>
            <p class="mt-1.5 text-xs text-slate-400">Bestimmt, welche Bereiche der Benutzer sehen darf.</p>
        </div>

        <div class="mt-6 flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                <i class="fa-solid fa-floppy-disk"></i> Rolle speichern
            </button>
            <a href="useroverview"
               class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                Abbrechen
            </a>
        </div>
    </form>
</div>

<?php require __DIR__ . '/../general/foot.php'; ?>
