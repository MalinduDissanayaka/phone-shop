{{-- Runs before CSS paints to avoid a flash of the wrong theme. Keep in sync with resources/js/theme.js. --}}
<script>
    (function () {
        var theme = null;
        try { theme = localStorage.getItem('theme'); } catch (e) {}
        var dark = theme ? theme === 'dark' : window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.classList.toggle('dark', dark);
    })();
</script>
