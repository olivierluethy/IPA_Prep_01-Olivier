<?php
$page = ['active' => '', 'title' => 'Profil & Einstellungen', 'icon' => 'fa-user'];
require __DIR__ . '/head.php';

$csrf    = $_SESSION['csrf'] ?? '';
$picture = ($user['picture'] ?? '') !== '' ? $user['picture'] : avatarDataUri($user['full_name'] ?? '');
?>

<div class="space-y-6 lg:grid lg:grid-cols-2 lg:gap-6 lg:space-y-0 lg:items-start">

    <!-- Karte 1: Profilbild & Konto -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <div class="mb-6 flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                <i class="fa-solid fa-id-badge"></i>
            </span>
            <div>
                <h2 class="font-serif text-lg font-semibold text-ink">Profilbild &amp; Konto</h2>
                <p class="text-sm text-slate-500">Verwalte deine persönlichen Angaben.</p>
            </div>
        </div>

        <form action="updateProfile" method="POST" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

            <div>
                <span class="mb-1.5 block text-sm font-medium text-slate-700">Profilbild</span>
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start">
                    <img id="avatarPreview" src="<?= e($picture) ?>" alt="Profilbild-Vorschau"
                         class="h-20 w-20 shrink-0 rounded-full object-cover ring-2 ring-slate-200">
                    <div class="min-w-0 flex-1 space-y-3">
                        <!-- Dropzone: Klick zum Auswählen oder Bild hierher ziehen -->
                        <label id="dropzone" for="picture"
                               class="flex cursor-pointer flex-col items-center justify-center gap-1 rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-center transition hover:border-brand-400 hover:bg-brand-50/40">
                            <i class="fa-solid fa-cloud-arrow-up text-lg text-slate-400"></i>
                            <span class="text-sm text-slate-600"><span class="font-medium text-brand-600">Bild wählen</span> oder hierher ziehen</span>
                            <span class="text-xs text-slate-400">JPG, PNG, WEBP oder GIF · max. 3 MB</span>
                            <input type="file" id="picture" name="picture" accept="image/*" class="sr-only">
                        </label>
                        <!-- Alternativ: Bild-URL einfügen -->
                        <div class="relative">
                            <i class="fa-solid fa-link pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="url" id="pictureUrl" name="picture_url" placeholder="Oder Bild-URL einfügen (https://…)"
                                   class="w-full rounded-lg border border-slate-300 py-2.5 pl-9 pr-11 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                            <button type="button" id="pasteUrlBtn" title="Bild-URL aus Zwischenablage einfügen"
                                    aria-label="Bild-URL aus Zwischenablage einfügen"
                                    class="absolute right-1.5 top-1/2 -translate-y-1/2 flex h-8 w-8 items-center justify-center rounded-md text-slate-400 transition hover:bg-brand-50 hover:text-brand-600 focus:outline-none focus:ring-2 focus:ring-brand-100">
                                <i class="fa-solid fa-paste"></i>
                            </button>
                        </div>
                        <p id="pasteUrlHint" class="hidden text-xs" aria-live="polite"></p>
                    </div>
                </div>
            </div>

            <div>
                <label for="first_name" class="mb-1.5 block text-sm font-medium text-slate-700">Vorname</label>
                <input type="text" id="first_name" name="first_name" value="<?= e($user['first_name'] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>

            <div>
                <label for="last_name" class="mb-1.5 block text-sm font-medium text-slate-700">Nachname</label>
                <input type="text" id="last_name" name="last_name" value="<?= e($user['last_name'] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>

            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">E-Mail</label>
                <input type="email" id="email" name="email" value="<?= e($user['email'] ?? '') ?>"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    <i class="fa-solid fa-floppy-disk"></i> Änderungen speichern
                </button>
            </div>
        </form>
    </div>

    <!-- Karte 2: Passwort ändern -->
    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-card">
        <div class="mb-6 flex items-center gap-3">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                <i class="fa-solid fa-lock"></i>
            </span>
            <div>
                <h2 class="font-serif text-lg font-semibold text-ink">Passwort ändern</h2>
                <p class="text-sm text-slate-500">Wähle ein sicheres neues Passwort.</p>
            </div>
        </div>

        <form action="updatePassword" method="POST" class="space-y-5">
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">

            <div>
                <label for="current_password" class="mb-1.5 block text-sm font-medium text-slate-700">Aktuelles Passwort</label>
                <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>

            <div>
                <label for="new_password" class="mb-1.5 block text-sm font-medium text-slate-700">Neues Passwort</label>
                <input type="password" id="new_password" name="new_password" autocomplete="new-password"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
                <p class="mt-1 text-xs text-slate-400">Mindestens 6 Zeichen.</p>
            </div>

            <div>
                <label for="confirm_password" class="mb-1.5 block text-sm font-medium text-slate-700">Passwort bestätigen</label>
                <input type="password" id="confirm_password" name="confirm_password" autocomplete="new-password"
                       class="w-full rounded-lg border border-slate-300 px-3 py-2.5 text-sm focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-100">
            </div>

            <div class="flex justify-end pt-1">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                    <i class="fa-solid fa-key"></i> Passwort aktualisieren
                </button>
            </div>
        </form>
    </div>

</div>

<script>
(function () {
    var input   = document.getElementById('picture');
    var url     = document.getElementById('pictureUrl');
    var preview = document.getElementById('avatarPreview');
    var dz      = document.getElementById('dropzone');
    if (!input || !preview) return;
    var fallback = preview.src;

    function showFile(file) {
        if (!file || !file.type || file.type.indexOf('image/') !== 0) return;
        preview.src = URL.createObjectURL(file);
    }

    input.addEventListener('change', function () {
        if (input.files && input.files[0]) {
            if (url) url.value = '';
            showFile(input.files[0]);
        }
    });

    if (url) {
        url.addEventListener('input', function () {
            var v = url.value.trim();
            if (v) {
                input.value = '';          // Datei-Auswahl zurücksetzen, wenn eine URL genutzt wird
                preview.src = v;           // Live-Vorschau der URL
            } else {
                preview.src = fallback;
            }
        });
    }

    // Bei ungültiger URL nicht mit kaputtem Bild stehen bleiben
    preview.addEventListener('error', function () {
        if (preview.src !== fallback) preview.src = fallback;
    });

    // Bild-URL aus der Zwischenablage einfügen (nur nach Klick, HTTPS/localhost).
    var pasteBtn  = document.getElementById('pasteUrlBtn');
    var pasteHint = document.getElementById('pasteUrlHint');
    var hintTimer;

    function showHint(msg, ok) {
        if (!pasteHint) return;
        pasteHint.textContent = msg;
        pasteHint.classList.remove('hidden', 'text-emerald-600', 'text-slate-500');
        pasteHint.classList.add(ok ? 'text-emerald-600' : 'text-slate-500');
        clearTimeout(hintTimer);
        hintTimer = setTimeout(function () { pasteHint.classList.add('hidden'); }, 3000);
    }

    function isHttpUrl(value) {
        try {
            var u = new URL(value);
            return u.protocol === 'http:' || u.protocol === 'https:';
        } catch (err) {
            return false;
        }
    }

    if (pasteBtn && url) {
        pasteBtn.addEventListener('click', function () {
            if (!navigator.clipboard || !navigator.clipboard.readText) {
                showHint('Zwischenablage kann in diesem Browser nicht gelesen werden.', false);
                return;
            }
            navigator.clipboard.readText().then(function (text) {
                var v = (text || '').trim();
                if (v && isHttpUrl(v)) {
                    url.value = v;
                    // Bestehende Logik übernehmen (Datei-Auswahl zurücksetzen + Vorschau).
                    url.dispatchEvent(new Event('input', { bubbles: true }));
                    showHint('URL eingefügt.', true);
                } else {
                    showHint('Keine gültige Bild-URL in der Zwischenablage gefunden.', false);
                }
            }).catch(function () {
                showHint('Keine gültige Bild-URL in der Zwischenablage gefunden.', false);
            });
        });
    }

    if (dz) {
        ['dragenter', 'dragover'].forEach(function (ev) {
            dz.addEventListener(ev, function (e) {
                e.preventDefault();
                dz.classList.add('border-brand-500', 'bg-brand-50');
            });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            dz.addEventListener(ev, function (e) {
                e.preventDefault();
                dz.classList.remove('border-brand-500', 'bg-brand-50');
            });
        });
        dz.addEventListener('drop', function (e) {
            var files = e.dataTransfer && e.dataTransfer.files;
            if (files && files.length) {
                try { input.files = files; } catch (err) { /* ältere Browser: ignorieren */ }
                if (url) url.value = '';
                showFile(files[0]);
            }
        });
    }
})();
</script>

<?php require __DIR__ . '/foot.php'; ?>
