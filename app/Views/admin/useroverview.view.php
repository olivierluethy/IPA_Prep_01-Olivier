<?php
$page = ['active' => 'useroverview', 'title' => 'Benutzerverwaltung', 'icon' => 'fa-users-gear'];
require __DIR__ . '/../general/head.php';

// Rollen-Badge (0=Lernender, 1=Fachkraft, 2=Administrator).
$roleBadge = function (int $role): string {
    $map = [
        0 => ['Lernender',     'bg-brand-50 text-brand-700 ring-brand-200'],
        1 => ['Fachkraft',     'bg-emerald-50 text-emerald-700 ring-emerald-200'],
        2 => ['Administrator', 'bg-amber-50 text-amber-700 ring-amber-200'],
    ];
    [$label, $cls] = $map[$role] ?? $map[0];
    return '<span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium ring-1 ' . $cls . '">' . e($label) . '</span>';
};
?>

<p class="mb-6 text-sm text-slate-500">Verwalte die Rollen der registrierten Benutzer oder entferne Konten.</p>

<?php if (empty($arrayUsers)): ?>
    <div class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center">
        <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-brand-50 text-brand-600">
            <i class="fa-solid fa-users text-lg"></i>
        </span>
        <h3 class="mt-4 font-medium text-ink">Noch keine Benutzer</h3>
        <p class="mx-auto mt-1 max-w-sm text-sm text-slate-500">Sobald sich Personen registrieren, erscheinen sie hier.</p>
    </div>
<?php else: ?>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-card">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-100 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                    <tr>
                        <th class="px-4 py-3 sm:px-6">Name</th>
                        <th class="px-4 py-3">E-Mail</th>
                        <th class="px-4 py-3">Rolle</th>
                        <th class="px-4 py-3">Erstellt</th>
                        <th class="px-4 py-3 text-right sm:px-6">Aktionen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php foreach ($arrayUsers as $u): ?>
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-4 py-3 sm:px-6">
                                <div class="flex items-center gap-3">
                                    <img src="<?= e(avatarDataUri($u['full_name'] ?? '')) ?>" alt="" class="h-9 w-9 shrink-0 rounded-full object-cover ring-1 ring-slate-200">
                                    <span class="font-medium text-ink"><?= e($u['full_name'] ?? '') ?></span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-slate-500"><?= e($u['email'] ?? '') ?></td>
                            <td class="px-4 py-3"><?= $roleBadge((int) $u['role']) ?></td>
                            <td class="px-4 py-3 text-slate-400"><?= e(formatDate($u['created_at'] ?? '')) ?></td>
                            <td class="px-4 py-3 sm:px-6">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="editUser?id=<?= (int) $u['benutzerId'] ?>"
                                       class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition hover:bg-slate-100">
                                        <i class="fa-solid fa-user-pen"></i> Bearbeiten
                                    </a>
                                    <a href="deleteUser?id=<?= (int) $u['benutzerId'] ?>"
                                       onclick="return confirm('Diesen Benutzer endgültig löschen?')"
                                       class="inline-flex items-center justify-center rounded-lg p-2 text-slate-400 transition hover:bg-rose-50 hover:text-rose-600" title="Löschen">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/../general/foot.php'; ?>
