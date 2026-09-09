            </div><!-- /max-w-5xl -->
        </main>
    </div><!-- /lg:pl-64 -->
</div><!-- /min-h-full -->

<script>
    function toggleNav(open) {
        var sb = document.getElementById('sidebar');
        var ov = document.getElementById('navOverlay');
        if (open) {
            sb.classList.remove('-translate-x-full');
            ov.classList.remove('hidden');
        } else {
            sb.classList.add('-translate-x-full');
            ov.classList.add('hidden');
        }
    }

    function toggleProfileMenu() {
        document.getElementById('profileDropdown').classList.toggle('hidden');
    }

    document.addEventListener('click', function (e) {
        var pm = document.getElementById('profileMenu');
        if (pm && !pm.contains(e.target)) {
            document.getElementById('profileDropdown').classList.add('hidden');
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            toggleNav(false);
            document.getElementById('profileDropdown').classList.add('hidden');
        }
    });
</script>
</body>
</html>
