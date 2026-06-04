@push('scriptCustom')
<script>
(function () {
    const editor = document.getElementById('langUiEditor');
    if (!editor) return;

    const saveUrl = editor.dataset.saveUrl;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content;
    const toast = document.getElementById('langUiToast');

    function showToast(message, ok) {
        if (!toast) return;
        toast.textContent = message;
        toast.className = 'langUiToast langUiToast--' + (ok ? 'ok' : 'err');
        toast.hidden = false;
        clearTimeout(showToast._t);
        showToast._t = setTimeout(() => { toast.hidden = true; }, 4500);
    }

    function setCollapse(section, open) {
        const btns = section?.querySelectorAll('.langUiSection_collapseBtn') ?? [];
        const body = section?.querySelector('.langUiSection_body');
        const notice = section?.querySelector('.langUiSection_notice');
        if (!btns.length || !body) return;
        btns.forEach(btn => btn.setAttribute('aria-expanded', open ? 'true' : 'false'));
        section.classList.toggle('langUiSection--collapsed', !open);
        body.hidden = !open;
        if (notice) notice.hidden = !open;
        if (typeof section._langUiStickyRemount === 'function') {
            section._langUiStickyRemount();
        }
    }

    function stickyTopOffset() {
        const v = getComputedStyle(editor).getPropertyValue('--lang-ui-sticky-top').trim();
        return v || '0px';
    }

    function initStickySectionHeaders() {
        editor.querySelectorAll('.langUiSection').forEach(section => {
            if (section.dataset.langUiStickyInit) return;

            const header = section.querySelector(':scope > .langUiSection_header');
            if (!header) return;

            section.dataset.langUiStickyInit = '1';

            const sentinel = document.createElement('div');
            sentinel.className = 'langUiSection_stickySentinel';
            sentinel.setAttribute('aria-hidden', 'true');
            section.insertBefore(sentinel, header);

            let observer = null;

            const remount = () => {
                if (observer) {
                    observer.disconnect();
                    observer = null;
                }
                header.classList.remove('langUiSection_header--stuck');

                if (section.classList.contains('langUiSection--collapsed')) {
                    return;
                }

                observer = new IntersectionObserver(([entry]) => {
                    header.classList.toggle('langUiSection_header--stuck', !entry.isIntersecting);
                }, {
                    threshold: 0,
                    rootMargin: '-' + stickyTopOffset() + ' 0px 0px 0px',
                });
                observer.observe(sentinel);
            };

            section._langUiStickyRemount = remount;
            remount();
        });
    }

    editor.querySelectorAll('.langUiSection_collapseBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const section = btn.closest('.langUiSection');
            const open = btn.getAttribute('aria-expanded') === 'true';
            setCollapse(section, !open);
        });
    });

    function setSaveBtnState(btn, state) {
        btn.classList.remove('is-saving', 'is-saved');
        btn.disabled = state === 'saving';
        if (state === 'saving') btn.classList.add('is-saving');
        if (state === 'saved') btn.classList.add('is-saved');
    }

    async function saveBundle(stem, btn, options = {}) {
        const quiet = options.quiet === true;
        const section = btn.closest('.langUiSection');
        const inputs = section?.querySelectorAll('[data-bundle-input="' + stem + '"]') ?? [];
        const keys = {};
        inputs.forEach(el => {
            const name = el.getAttribute('name');
            const m = name && name.match(/keys\[(.+)\]/);
            if (m) keys[m[1]] = el.value;
        });

        setSaveBtnState(btn, 'saving');

        try {
            const res = await fetch(saveUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    bundle: stem,
                    keys,
                    section_id: btn.dataset.sectionId || '',
                }),
            });
            const data = await res.json();
            if (!res.ok || !data.success) {
                throw new Error(data.message || 'Lưu thất bại');
            }
            if (!quiet) {
                showToast(data.message, true);
                setSaveBtnState(btn, 'saved');
                setTimeout(() => setSaveBtnState(btn, 'idle'), 2200);
            } else {
                setSaveBtnState(btn, 'idle');
            }
            return { success: true, message: data.message || 'Đã lưu' };
        } catch (e) {
            if (!quiet) showToast(e.message || 'Lỗi kết nối', false);
            setSaveBtnState(btn, 'idle');
            throw e;
        }
    }

    editor.langUiSaveSection = async function (sectionId, options = {}) {
        const section = editor.querySelector('.langUiSection[data-section-id="' + sectionId + '"]');
        const btn = section?.querySelector('[data-bundle-save]');
        if (!btn) {
            throw new Error('Không tìm thấy section để lưu');
        }
        return saveBundle(btn.dataset.bundleSave, btn, options);
    };

    editor.querySelectorAll('[data-bundle-save]').forEach(btn => {
        btn.addEventListener('click', () => saveBundle(btn.dataset.bundleSave, btn));
    });

    const MIN_TEXTAREA_PX = 44;

    function syncRowHeights(row) {
        const areas = row.querySelectorAll('.langUiField_textarea');
        if (!areas.length) return;
        let maxH = MIN_TEXTAREA_PX;
        areas.forEach(ta => {
            ta.style.height = 'auto';
            maxH = Math.max(maxH, ta.scrollHeight);
        });
        areas.forEach(ta => {
            ta.style.height = maxH + 'px';
        });
    }

    function initLangUiTextareas(root) {
        root.querySelectorAll('[data-lang-ui-row]').forEach(row => {
            syncRowHeights(row);
            row.querySelectorAll('[data-lang-ui-edit]').forEach(ta => {
                ta.addEventListener('input', () => syncRowHeights(row));
            });
        });
    }

    initLangUiTextareas(editor);
    initStickySectionHeaders();

    let resizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(() => {
            initLangUiTextareas(editor);
            editor.querySelectorAll('.langUiSection').forEach(section => {
                if (typeof section._langUiStickyRemount === 'function') {
                    section._langUiStickyRemount();
                }
            });
        }, 120);
    });
})();
</script>
@endpush
