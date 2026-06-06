@push('scriptCustom')
<script>
(function () {
    const editor = document.getElementById('langUiEditor');
    const importUrl = editor?.dataset.importUrl;
    if (!editor || !importUrl) return;

    const scope = editor.dataset.aiScope || '';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const toast = document.getElementById('langUiToast');
    const modal = document.getElementById('langUiModal_import');
    let activeSectionId = null;

    function showToast(msg, ok) {
        if (!toast) return;
        toast.textContent = msg;
        toast.className = 'langUiToast langUiToast--' + (ok ? 'ok' : 'err');
        toast.hidden = false;
        clearTimeout(showToast._t);
        showToast._t = setTimeout(() => { toast.hidden = true; }, 5000);
    }

    function parseJson(res) {
        return res.text().then(text => {
            let json;
            try { json = JSON.parse(text); } catch (e) {
                throw new Error('Phản hồi không hợp lệ (HTTP ' + res.status + ')');
            }
            if (!res.ok) throw new Error(json.message || ('HTTP ' + res.status));
            return json;
        });
    }

    function postJson(url, body) {
        return fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(Object.assign({ scope }, body)),
        }).then(parseJson);
    }

    function parseImportPayload(raw) {
        let text = (raw || '').trim();
        if (!text) return { map: null, raw: '' };
        const fenced = text.match(/^```(?:json|php)?\s*\n?([\s\S]*?)\n?```$/i);
        if (fenced) text = fenced[1].trim();
        try {
            const decoded = JSON.parse(text);
            if (decoded !== null && typeof decoded === 'object') {
                return { map: decoded, raw: text };
            }
        } catch (_) { /* fallback */ }
        return { map: null, raw: text };
    }

    function getSectionEl(sectionId) {
        return editor.querySelector('.langUiSection[data-section-id="' + sectionId + '"]');
    }

    function sectionLabel(sectionId) {
        const el = getSectionEl(sectionId);
        const title = el?.querySelector('.companyManagementPage_section_title');
        return title ? title.textContent.replace(/\s+/g, ' ').trim() : sectionId;
    }

    function applyTranslation(key, text) {
        const ta = editor.querySelector('textarea[name="keys[' + key + ']"]');
        if (!ta) return false;
        ta.value = text;
        ta.dispatchEvent(new Event('input', { bubbles: true }));
        const row = ta.closest('.langUiField');
        if (row) {
            row.classList.remove('langUiField--ai-error');
            row.classList.add('langUiField--ai-done');
        }
        return true;
    }

    function applyImportMap(map) {
        let n = 0;
        Object.keys(map || {}).forEach(key => {
            if (applyTranslation(key, map[key])) n++;
        });
        return n;
    }

    function openModal() {
        if (!modal) return;
        modal.hidden = false;
        document.body.classList.add('langUiModal-open');
    }

    function closeModal() {
        if (!modal) return;
        modal.hidden = true;
        if (!document.querySelector('.langUiModal:not([hidden])')) {
            document.body.classList.remove('langUiModal-open');
        }
    }

    modal?.querySelectorAll('[data-lang-ui-modal-close]').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    async function openImport(sectionId) {
        activeSectionId = sectionId;
        const ctx = document.getElementById('langUiModal_import_context');
        if (ctx) {
            ctx.textContent = 'Nhập JSON cho section: ' + sectionLabel(sectionId);
        }
        const ta = document.getElementById('langUiModal_import_text');
        if (ta) ta.value = '';
        openModal();
    }

    editor.querySelectorAll('[data-action="import"]').forEach(btn => {
        btn.addEventListener('click', async () => {
            const section = btn.closest('.langUiSection');
            const sectionId = section?.dataset.sectionId;
            if (!sectionId) return;
            await openImport(sectionId);
        });
    });

    document.getElementById('langUiModal_import_run')?.addEventListener('click', async () => {
        if (!activeSectionId) return;
        const pasted = document.getElementById('langUiModal_import_text')?.value || '';
        const { map: localMap, raw } = parseImportPayload(pasted);
        const requestBody = { section_id: activeSectionId };
        if (localMap) requestBody.payload_map = localMap;
        else requestBody.payload = raw;
        try {
            const json = await postJson(importUrl, requestBody);
            const n = applyImportMap(json.data?.translated || {});
            closeModal();
            const warnings = json.data?.warnings || [];
            if (warnings.length) {
                showToast(warnings[0] + ' Kiểm tra và lưu section.', false);
            } else {
                showToast('Đã nhập ' + n + ' trường. Kiểm tra và lưu.', true);
            }
        } catch (e) {
            showToast(e.message, false);
        }
    });
})();
</script>
@endpush
