{{-- JS tối thiểu — shell liendoan (menu mobile, toast, loading) --}}
<script>
    function setFullLoading(active) {
        const el = document.getElementById('js_fullLoading_bg');
        const blur = document.getElementById('js_fullLoading_blur');
        if (! el || ! blur) return;

        if (active) {
            el.classList.add('is-active');
            el.removeAttribute('hidden');
            el.setAttribute('aria-hidden', 'false');
            blur.style.filter = 'blur(8px)';
            blur.style.overflow = 'hidden';
        } else {
            el.classList.remove('is-active');
            el.setAttribute('hidden', '');
            el.setAttribute('aria-hidden', 'true');
            blur.style.filter = '';
            blur.style.overflow = '';
        }
    }

    function openCloseFullLoading() {
        const el = document.getElementById('js_fullLoading_bg');
        if (! el) return;
        setFullLoading(! el.classList.contains('is-active'));
    }

    function createToast(type, title, message) {
        const container = document.getElementById('toast-container');
        if (! container) return;
        const toastId = 'toast-' + Date.now();
        container.insertAdjacentHTML('beforeend', `
            <div id="${toastId}" class="toast toast-${type}" style="display:block;opacity:1">
                <button type="button" class="toast-close-button" onclick="document.getElementById('${toastId}').remove()">×</button>
                <div class="toast-title">${title}</div>
                <div class="toast-message">${message}</div>
            </div>`);
        setTimeout(() => document.getElementById(toastId)?.remove(), 8000);
    }

    function toggleAdminMobileMenu() {
        const sidebar = document.getElementById('adminDashboardSidebar');
        const backdrop = document.getElementById('adminMobileMenuBackdrop');
        const isOpen = sidebar?.classList.contains('adminDashboard_sidebar--mobileOpen');
        if (! isOpen) {
            sidebar?.classList.add('adminDashboard_sidebar--mobileOpen');
            backdrop?.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            sidebar?.classList.remove('adminDashboard_sidebar--mobileOpen');
            backdrop?.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        setFullLoading(false);

        document.getElementById('adminMobileMenuBackdrop')?.addEventListener('click', toggleAdminMobileMenu);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('adminDashboardSidebar');
                if (sidebar?.classList.contains('adminDashboard_sidebar--mobileOpen')) {
                    toggleAdminMobileMenu();
                }
            }
        });
    });
</script>
