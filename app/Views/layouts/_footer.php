<footer class="text-center mt-5 py-4 text-muted border-top">
    <div class="container">
        <small>&copy;
            <?= date('Y') ?> คณะวิทยาศาสตร์และเทคโนโลยี มรภ.เพชรบูรณ์
        </small>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Theme Toggle Logic
    window.addEventListener('DOMContentLoaded', () => {
        // Chart.js Theme Helper
        window.getChartColors = () => {
            const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
            return {
                grid: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                text: isDark ? '#adb5bd' : '#6c757d',
                primary: '#0d6efd'
            };
        };

        const showActiveTheme = (theme, focus = false) => {
            const themeToggle = document.querySelector('#bd-theme')
            if (!themeToggle) return

            const activeThemeIcon = themeToggle.querySelector('i')
            const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
            
            document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                element.classList.remove('active')
                element.setAttribute('aria-pressed', 'false')
            })

            btnToActive.classList.add('active')
            btnToActive.setAttribute('aria-pressed', 'true')
            
            if (theme === 'dark') {
                activeThemeIcon.className = 'fas fa-moon mb-1'
            } else if (theme === 'light') {
                activeThemeIcon.className = 'fas fa-sun mb-1'
            } else {
                activeThemeIcon.className = 'fas fa-circle-half-stroke mb-1'
            }

            if (focus) {
                themeToggle.focus()
            }

            // Dispatch event for components that need to re-render (like Charts)
            window.dispatchEvent(new Event('themeChanged'));
        }

        document.querySelectorAll('[data-bs-theme-value]')
            .forEach(toggle => {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value')
                    setStoredTheme(theme)
                    setTheme(theme)
                    showActiveTheme(theme, true)
                })
            })

        showActiveTheme(getPreferredTheme())
    })
</script>