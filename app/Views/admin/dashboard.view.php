<?php
$page = ['active' => 'home', 'title' => 'Dashboard', 'icon' => 'fa-gauge-high'];
require __DIR__ . '/../general/head.php';

$firstName = $_SESSION['first_name'] ?? '';

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

$lernendeCount = count(array_filter($users, fn($u) => (int) $u['role'] === 0));
$fachkraftCount = count(array_filter($users, fn($u) => (int) $u['role'] === 1));
?>

<!-- Begrüßung -->
<section class="mb-8 overflow-hidden rounded-2xl bg-brand-950 p-6 text-white sm:p-8">
    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-brand-200">Hallo <?= e($firstName) ?>, willkommen zurück.</p>
            <h2 class="mt-1 font-serif text-2xl font-semibold">Verwalte die Benutzer des Lernjournals.</h2>
        </div>
        <a href="useroverview"
           class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-500">
            <i class="fa-solid fa-users-gear"></i> Benutzer verwalten
        </a>
    </div>
</section>

<!-- Kennzahlen -->
<section class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
    <?php
    $tiles = [
        ['fa-users',        'text-brand-600 bg-brand-50',     count($users),    'Benutzer gesamt'],
        ['fa-user-graduate','text-indigo-600 bg-indigo-50',   $lernendeCount,   'Lernende'],
        ['fa-user-tie',     'text-emerald-600 bg-emerald-50', $fachkraftCount,  'Fachkräfte'],
    ];
    foreach ($tiles as [$icon, $tint, $value, $label]): ?>
        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-card">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg <?= $tint ?>">
                <i class="fa-solid <?= $icon ?>"></i>
            </span>
            <p class="mt-3 text-2xl font-semibold text-ink"><?= (int) $value ?></p>
            <p class="text-sm text-slate-500"><?= e($label) ?></p>
        </div>
    <?php endforeach; ?>
</section>

<!-- Zuletzt angelegte Benutzer -->
<section>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="font-serif text-xl font-semibold text-ink">Benutzer</h2>
        <a href="useroverview" class="text-sm font-medium text-brand-600 hover:text-brand-700">Alle verwalten</a>
    </div>

    <?php if (empty($users)): ?>
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
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php foreach (array_slice($users, 0, 8) as $u): ?>
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-4 py-3 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <img src="<?= e(avatarDataUri($u['full_name'] ?? '')) ?>" alt="" class="h-8 w-8 shrink-0 rounded-full object-cover ring-1 ring-slate-200">
                                        <span class="font-medium text-ink"><?= e($u['full_name'] ?? '') ?></span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-slate-500"><?= e($u['email'] ?? '') ?></td>
                                <td class="px-4 py-3"><?= $roleBadge((int) $u['role']) ?></td>
                                <td class="px-4 py-3 text-slate-400"><?= e(formatDate($u['created_at'] ?? '')) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../general/foot.php'; ?>
