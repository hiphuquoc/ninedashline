@if ($isMaster ?? false)
@push('scriptCustom')
<script>
(function () {
    const editor = document.getElementById('langUiEditor');
    if (!editor || !editor.dataset.aiHorizontalUrl) return;

    const scope = editor.dataset.aiScope;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const toast = document.getElementById('langUiToast');
    const aiEnabled = editor.dataset.aiEnabled === '1';
    const horizontalUrl = editor.dataset.aiHorizontalUrl;
    const configUrl = editor.dataset.aiConfigUrl;
    let targetLocales = [];
    try {
        targetLocales = JSON.parse(editor.dataset.horizontalTargets || '[]');
    } catch (_) {
        targetLocales = [];
    }

    const MODEL_KEY = 'langUi_ai_model_horizontal_' + scope;
    let aiConfig = null;
    let activeSectionId = null;
    let horizontalAbort = false;
    let horizontalRunning = false;

    const modals = {
        confirm: document.getElementById('langUiModal_horizontal'),
        progress: document.getElementById('langUiModal_horizontal_progress'),
    };

    function showToast(msg, ok) {
        if (!toast) return;
        toast.textContent = msg;
        toast.className = 'langUiToast langUiToast--' + (ok ? 'ok' : 'err');
        toast.hidden = false;
        clearTimeout(showToast._t);
        showToast._t = setTimeout(() => { toast.hidden = true; }, 7000);
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

    async function loadAiConfig() {
        if (aiConfig) return aiConfig;
        const u = configUrl + (configUrl.includes('?') ? '&' : '?') + 'scope=' + encodeURIComponent(scope);
        const json = await fetch(u, { headers: { Accept: 'application/json' } }).then(parseJson);
        aiConfig = json.data || {};
        return aiConfig;
    }

    function fillModelSelect(sel, preferred) {
        if (!sel) return;
        const models = aiConfig?.models || [];
        const saved = preferred || localStorage.getItem(MODEL_KEY) || aiConfig?.default_model || '';
        sel.innerHTML = models.length
            ? models.map(m => '<option value="' + m.replace(/"/g, '&quot;') + '">' + m + '</option>').join('')
            : '<option value="">—</option>';
        sel.disabled = models.length === 0;
        if (saved && models.includes(saved)) sel.value = saved;
        else if (models[0]) sel.value = models[0];
    }

    function getSectionEl(sectionId) {
        return editor.querySelector('.langUiSection[data-section-id="' + sectionId + '"]');
    }

    function sectionLabel(sectionId) {
        const row = (aiConfig?.sections || []).find(s => s.id === sectionId);
        const el = getSectionEl(sectionId);
        const title = el?.querySelector('.companyManagementPage_section_title');
        return row?.label || (title ? title.textContent.trim() : sectionId);
    }

    function countSectionFields(sectionId) {
        const section = getSectionEl(sectionId);
        if (!section) return 0;
        let n = 0;
        section.querySelectorAll('.langUiField[data-key] textarea').forEach(ta => {
            if (ta.value.trim() !== '') n++;
        });
        return n;
    }

    function setSectionLocked(sectionId, locked, statusText) {
        const section = getSectionEl(sectionId);
        if (!section) return;
        const lock = section.querySelector('[data-lang-ui-section-lock]');
        const workzone = section.querySelector('.langUiSection_workzone');
        if (lock) {
            lock.hidden = !locked;
            lock.setAttribute('aria-hidden', locked ? 'false' : 'true');
            const st = lock.querySelector('[data-lang-ui-lock-status]');
            if (st && statusText) st.textContent = statusText;
        }
        section.classList.toggle('langUiSection--locked', locked);
        if (workzone) workzone.setAttribute('aria-busy', locked ? 'true' : 'false');
        section.querySelectorAll('.langUiSection_header_actions button').forEach(el => {
            if (locked) {
                el.dataset._wasDisabled = el.disabled ? '1' : '0';
                el.disabled = true;
            } else if (el.dataset._wasDisabled !== undefined) {
                el.disabled = el.dataset._wasDisabled === '1';
                delete el.dataset._wasDisabled;
            }
        });
    }

    function openModal(which) {
        const m = modals[which];
        if (!m) return;
        m.hidden = false;
        document.body.classList.add('langUiModal-open');
    }

    function closeModal(which) {
        const m = modals[which];
        if (m) m.hidden = true;
        if (!modals.confirm?.hidden && !modals.progress?.hidden) return;
        if (modals.confirm?.hidden && modals.progress?.hidden) {
            document.body.classList.remove('langUiModal-open');
        }
    }

    function closeAllModals() {
        Object.values(modals).forEach(m => { if (m) m.hidden = true; });
        document.body.classList.remove('langUiModal-open');
    }

    document.querySelectorAll('[data-lang-ui-modal-close]').forEach(btn => {
        btn.addEventListener('click', () => {
            if (horizontalRunning) return;
            closeAllModals();
        });
    });

    function updateProgress(done, total, statusText) {
        const pct = total > 0 ? Math.round((done / total) * 100) : 0;
        const fill = document.getElementById('langUiModal_horizontal_progress_fill');
        const bar = fill?.parentElement;
        const status = document.getElementById('langUiModal_horizontal_status');
        if (fill) fill.style.width = pct + '%';
        if (bar) bar.setAttribute('aria-valuenow', String(pct));
        if (status && statusText) status.textContent = statusText;
    }

    function localeDisplay(loc) {
        const code = (loc.code || '').toUpperCase();
        const name = loc.name_vi || loc.name || code;
        return code + ' — ' + name;
    }

    function renderReport(rows, sectionId, cancelled) {
        const report = document.getElementById('langUiModal_horizontal_report');
        const summary = document.getElementById('langUiModal_horizontal_summary');
        const tbody = document.getElementById('langUiModal_horizontal_tbody');
        const title = document.getElementById('langUiModal_horizontal_progress_title');
        const doneBtn = document.getElementById('langUiModal_horizontal_done');
        const cancelBtn = document.getElementById('langUiModal_horizontal_cancel');
        const closeBtn = document.getElementById('langUiModal_horizontal_progress_close');

        if (!report || !tbody) return;

        const ok = rows.filter(r => r.ok).length;
        const fail = rows.filter(r => !r.ok).length;
        const warn = rows.filter(r => r.ok && (r.warnings || []).length).length;

        if (title) {
            title.innerHTML = cancelled
                ? '<i class="fa-solid fa-circle-stop"></i> Đã hủy dịch ngang'
                : (fail ? '<i class="fa-solid fa-triangle-exclamation"></i> Hoàn tất — có lỗi' : '<i class="fa-solid fa-circle-check"></i> Hoàn tất dịch ngang');
        }
        if (summary) {
            summary.innerHTML =
                '<strong>' + sectionLabel(sectionId) + '</strong> — ' +
                ok + ' thành công, ' + fail + ' lỗi' +
                (warn ? (', ' + warn + ' có cảnh báo') : '') +
                (cancelled ? ' · đã hủy giữa chừng' : '');
        }

        tbody.innerHTML = rows.map(r => {
            const status = r.ok
                ? '<span class="langUiHorizontal_badge langUiHorizontal_badge--ok">OK</span>'
                : '<span class="langUiHorizontal_badge langUiHorizontal_badge--err">Lỗi</span>';
            const fields = r.ok
                ? (r.imported + '/' + r.key_count)
                : '—';
            let note = '';
            if (!r.ok) note = r.error || 'Không xác định';
            else if ((r.warnings || []).length) note = r.warnings.join(' ');
            else note = 'Đã lưu';
            return '<tr class="' + (r.ok ? 'langUiHorizontal_row--ok' : 'langUiHorizontal_row--err') + '">' +
                '<td><code>' + (r.code || '').toUpperCase() + '</code></td>' +
                '<td>' + status + '</td>' +
                '<td>' + fields + '</td>' +
                '<td class="langUiHorizontal_note">' + note.replace(/</g, '&lt;') + '</td>' +
                '</tr>';
        }).join('');

        report.hidden = false;
        if (doneBtn) doneBtn.hidden = false;
        if (cancelBtn) cancelBtn.hidden = true;
        if (closeBtn) closeBtn.hidden = false;
    }

    async function runHorizontalTranslate(sectionId, model, debug) {
        if (!targetLocales.length) {
            showToast('Không có locale đích.', false);
            return;
        }

        horizontalAbort = false;
        horizontalRunning = true;
        const rows = [];
        const total = targetLocales.length;
        let done = 0;

        closeModal('confirm');
        openModal('progress');

        const ctx = document.getElementById('langUiModal_horizontal_progress_context');
        const report = document.getElementById('langUiModal_horizontal_report');
        const doneBtn = document.getElementById('langUiModal_horizontal_done');
        const cancelBtn = document.getElementById('langUiModal_horizontal_cancel');
        const closeBtn = document.getElementById('langUiModal_horizontal_progress_close');
        if (ctx) ctx.textContent = sectionLabel(sectionId) + ' → ' + total + ' locale';
        if (report) report.hidden = true;
        if (doneBtn) doneBtn.hidden = true;
        if (cancelBtn) cancelBtn.hidden = false;
        if (closeBtn) closeBtn.hidden = true;

        setSectionLocked(sectionId, true, 'Dịch ngang 0/' + total + ' locale…');
        updateProgress(0, total, 'Bắt đầu…');

        for (let i = 0; i < targetLocales.length; i++) {
            if (horizontalAbort) break;
            const loc = targetLocales[i];
            const code = loc.code;
            const label = localeDisplay(loc);
            const isLast = i === targetLocales.length - 1;

            setSectionLocked(sectionId, true, 'Dịch ngang ' + (i + 1) + '/' + total + ': ' + code);
            updateProgress(done, total, 'Đang dịch ' + label + ' (' + (i + 1) + '/' + total + ')…');
            showToast('Dịch ngang: ' + label + ' (' + (i + 1) + '/' + total + ')', true);

            try {
                const json = await postJson(horizontalUrl, {
                    section_id: sectionId,
                    target_locale: code,
                    model,
                    debug,
                    clear_config: isLast && !horizontalAbort,
                });
                if (json.data?.debug) console.log('[AI horizontal]', code, json.data.debug);
                const data = json.data || {};
                rows.push({
                    code,
                    ok: true,
                    imported: data.imported || 0,
                    key_count: data.key_count || 0,
                    warnings: data.warnings || [],
                });
            } catch (e) {
                rows.push({
                    code,
                    ok: false,
                    error: e.message || String(e),
                });
                console.error('[horizontal]', code, e);
            }

            done++;
            updateProgress(done, total, horizontalAbort
                ? 'Đã hủy sau ' + done + '/' + total + ' locale.'
                : ('Xong ' + done + '/' + total + ' — ' + label));

            if (i < targetLocales.length - 1 && !horizontalAbort) {
                await new Promise(r => setTimeout(r, 400));
            }
        }

        if (rows.some(r => r.ok)) {
            try {
                await postJson(horizontalUrl, { config_only: true });
            } catch (_) { /* cache refresh best-effort */ }
        }

        setSectionLocked(sectionId, false);
        horizontalRunning = false;
        renderReport(rows, sectionId, horizontalAbort);

        const ok = rows.filter(r => r.ok).length;
        const fail = rows.filter(r => !r.ok).length;
        const msg = horizontalAbort
            ? 'Hủy dịch ngang — ' + ok + ' locale đã lưu, ' + fail + ' lỗi.'
            : ('Dịch ngang xong: ' + ok + ' locale OK' + (fail ? ', ' + fail + ' lỗi' : '') + '. Xem báo cáo chi tiết.');
        showToast(msg, !horizontalAbort && fail === 0);
    }

    editor.querySelectorAll('[data-lang-ui-horizontal-toolbar] [data-action="horizontal-ai"]').forEach(btn => {
        btn.addEventListener('click', async () => {
            const section = btn.closest('.langUiSection');
            activeSectionId = section?.dataset.sectionId || null;
            if (!activeSectionId) return;
            if (!aiEnabled) {
                showToast('Bật AI_ENABLED và API key trong .env', false);
                return;
            }
            await loadAiConfig();
            const fields = countSectionFields(activeSectionId);
            const ctx = document.getElementById('langUiModal_horizontal_context');
            if (ctx) {
                ctx.textContent = sectionLabel(activeSectionId) + ' — ' + fields + ' trường VI → ' + targetLocales.length + ' locale (tự lưu).';
            }
            fillModelSelect(document.getElementById('langUiModal_horizontal_model'));
            openModal('confirm');
        });
    });

    document.getElementById('langUiModal_horizontal_run')?.addEventListener('click', async () => {
        if (!activeSectionId) return;
        const model = document.getElementById('langUiModal_horizontal_model')?.value;
        if (!model) {
            showToast('Chưa chọn mô hình AI', false);
            return;
        }
        localStorage.setItem(MODEL_KEY, model);
        const debug = document.getElementById('langUiModal_horizontal_debug')?.checked;
        try {
            await runHorizontalTranslate(activeSectionId, model, debug);
        } catch (e) {
            horizontalRunning = false;
            setSectionLocked(activeSectionId, false);
            closeAllModals();
            showToast(e.message, false);
        }
    });

    document.getElementById('langUiModal_horizontal_cancel')?.addEventListener('click', () => {
        if (horizontalRunning) {
            horizontalAbort = true;
            const status = document.getElementById('langUiModal_horizontal_status');
            if (status) status.textContent = 'Đang hủy sau locale hiện tại…';
            return;
        }
        closeAllModals();
    });

    document.getElementById('langUiModal_horizontal_done')?.addEventListener('click', closeAllModals);
    document.getElementById('langUiModal_horizontal_progress_close')?.addEventListener('click', () => {
        if (!horizontalRunning) closeAllModals();
    });

    loadAiConfig().then(() => {
        fillModelSelect(document.getElementById('langUiModal_horizontal_model'));
    }).catch(() => {});
})();
</script>
@endpush
@endif
