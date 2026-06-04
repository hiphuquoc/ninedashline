@push('scriptCustom')
<script>
(function () {
    const editor = document.getElementById('langUiEditor');
    if (!editor || !editor.dataset.aiScope) return;

    const scope = editor.dataset.aiScope;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    const toast = document.getElementById('langUiToast');
    const aiEnabled = editor.dataset.aiEnabled === '1';
    const urls = {
        config: editor.dataset.aiConfigUrl,
        aiSection: editor.dataset.aiTranslateSectionUrl,
        google: editor.dataset.googleTranslateUrl,
        export: editor.dataset.exportPromptUrl,
        import: editor.dataset.importUrl,
    };

    const MODEL_KEY = 'langUi_ai_model_' + scope;

    let aiConfig = null;
    let activeSectionId = null;
    let batchAbort = false;
    let globalRun = false;

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

    async function loadAiConfig() {
        if (aiConfig) return aiConfig;
        const u = urls.config + (urls.config.includes('?') ? '&' : '?') + 'scope=' + encodeURIComponent(scope);
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

    function listSectionIds() {
        const seen = new Set();
        const ids = [];
        editor.querySelectorAll('.langUiSection[data-section-id]').forEach(el => {
            const id = el.dataset.sectionId;
            if (!id || seen.has(id)) return;
            seen.add(id);
            ids.push(id);
        });
        return ids;
    }

    function collectJobs(sectionId) {
        const section = getSectionEl(sectionId);
        if (!section) return [];
        const jobs = [];
        section.querySelectorAll('.langUiField[data-key]').forEach(row => {
            const key = row.dataset.key;
            const ref = row.querySelector('[data-lang-ui-ref]');
            const edit = row.querySelector('[data-lang-ui-edit]');
            if (!key || !edit) return;
            const vi = ref ? ref.value.trim() : '';
            if (vi === '') return;
            jobs.push({ key, vi, editEl: edit, rowEl: row });
        });
        return jobs;
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
        const lockCancel = section.querySelector('[data-lang-ui-lock-cancel]');
        section.querySelectorAll('.langUiSection_body button, .langUiSection_body textarea, .langUiSection_header_actions button').forEach(el => {
            if (el === lockCancel) return;
            if (locked) {
                el.dataset._wasDisabled = el.disabled ? '1' : '0';
                el.disabled = true;
            } else if (el.dataset._wasDisabled !== undefined) {
                el.disabled = el.dataset._wasDisabled === '1';
                delete el.dataset._wasDisabled;
            }
        });
        if (lockCancel) lockCancel.disabled = false;
        section.querySelectorAll('.langUiSection_toolBtn, .langUiSection_collapseBtn').forEach(el => {
            if (el === lockCancel) return;
            if (locked) {
                el.dataset._wasDisabled = el.disabled ? '1' : '0';
                el.disabled = true;
            } else if (el.dataset._wasDisabled !== undefined) {
                el.disabled = el.dataset._wasDisabled === '1';
                delete el.dataset._wasDisabled;
            }
        });
    }

    function setGlobalBusy(busy, statusText) {
        globalRun = busy;
        const actions = document.getElementById('langUiActions');
        const cancel = document.getElementById('langUiActions_cancel');
        const allBtn = document.getElementById('langUiActions_ai_all');
        const modelSel = document.getElementById('langUiActions_ai_model');
        if (actions) actions.classList.toggle('langUiActions--busy', busy);
        if (cancel) cancel.hidden = !busy;
        if (allBtn) allBtn.disabled = busy || !aiEnabled;
        if (modelSel) modelSel.disabled = busy || modelSel.options.length === 0;
        if (busy && statusText && cancel) cancel.title = statusText;
    }

    async function runSequential(sectionId, jobs, translateOne) {
        batchAbort = false;
        let done = 0;
        const total = jobs.length;
        let errors = 0;

        setSectionLocked(sectionId, true, 'Đang dịch 0/' + total + '…');

        for (const job of jobs) {
            if (batchAbort) break;
            done++;
            setSectionLocked(sectionId, true, 'Đang dịch ' + done + '/' + total + ': ' + job.key);
            job.rowEl?.classList.add('langUiField--ai-translating');
            try {
                const text = await translateOne(job);
                applyTranslation(job.key, text);
            } catch (e) {
                errors++;
                job.rowEl?.classList.add('langUiField--ai-error');
                console.error(job.key, e);
            } finally {
                job.rowEl?.classList.remove('langUiField--ai-translating');
            }
            await new Promise(r => setTimeout(r, 120));
        }

        setSectionLocked(sectionId, false);
        const msg = batchAbort
            ? 'Đã hủy dịch.'
            : (errors ? ('Xong — ' + errors + ' lỗi. Kiểm tra và lưu section.') : 'Dịch xong. Nhớ bấm Lưu section.');
        showToast(msg, !batchAbort && errors === 0);
    }

    async function runSectionAiTranslate(sectionId, model, debug, options = {}) {
        const { autoSave = false, quiet = false } = options;
        const jobs = collectJobs(sectionId);
        if (jobs.length === 0) {
            if (!quiet) showToast('Section không có trường VI', false);
            return { ok: false, skipped: true, saved: false };
        }

        setSectionLocked(sectionId, true, 'AI đang dịch cả section (Copy Prompt)…');
        try {
            const json = await postJson(urls.aiSection, {
                section_id: sectionId,
                model,
                debug,
            });
            if (json.data?.debug) console.log('[AI section]', sectionId, json.data.debug);
            const map = json.data?.translated || {};
            const n = applyImportMap(map);
            const warnings = json.data?.warnings || [];
            let saved = false;

            if (autoSave && typeof editor.langUiSaveSection === 'function') {
                setSectionLocked(sectionId, true, 'Đang lưu section…');
                await editor.langUiSaveSection(sectionId, { quiet: true });
                saved = true;
            }

            setSectionLocked(sectionId, false);

            if (!quiet) {
                if (warnings.length) {
                    showToast(warnings[0], false);
                } else if (autoSave && saved) {
                    showToast('Đã dịch và lưu ' + n + '/' + jobs.length + ' trường.', n === jobs.length);
                } else {
                    showToast('Đã nhập ' + n + '/' + jobs.length + ' trường. Kiểm tra và lưu.', n === jobs.length);
                }
            }

            return { ok: true, filled: n, total: jobs.length, saved };
        } catch (e) {
            setSectionLocked(sectionId, false);
            throw e;
        }
    }

    const modals = {
        ai: document.getElementById('langUiModal_ai'),
        google: document.getElementById('langUiModal_google'),
        export: document.getElementById('langUiModal_export'),
        import: document.getElementById('langUiModal_import'),
    };

    function openModal(which) {
        const m = modals[which];
        if (!m) return;
        m.hidden = false;
        document.body.classList.add('langUiModal-open');
    }
    function closeAllModals() {
        Object.values(modals).forEach(m => { if (m) m.hidden = true; });
        document.body.classList.remove('langUiModal-open');
    }

    document.querySelectorAll('[data-lang-ui-modal-close]').forEach(btn => {
        btn.addEventListener('click', closeAllModals);
    });

    editor.querySelectorAll('[data-lang-ui-translate-toolbar] [data-action]').forEach(btn => {
        btn.addEventListener('click', async () => {
            const section = btn.closest('.langUiSection');
            activeSectionId = section?.dataset.sectionId || null;
            if (!activeSectionId) return;
            const action = btn.dataset.action;
            if (btn.disabled && action === 'ai-bulk') {
                showToast('Bật AI_ENABLED và API key trong .env', false);
                return;
            }
            if (action === 'ai-bulk') await openAiBulk(activeSectionId);
            else if (action === 'google-bulk') await openGoogleBulk(activeSectionId);
            else if (action === 'export') await openExport(activeSectionId);
            else if (action === 'import') await openImport(activeSectionId);
        });
    });

    editor.querySelectorAll('[data-lang-ui-lock-cancel]').forEach(btn => {
        btn.addEventListener('click', () => { batchAbort = true; });
    });

    document.querySelectorAll('[data-lang-ui-global-cancel]').forEach(btn => {
        btn.addEventListener('click', () => { batchAbort = true; });
    });

    function sectionLabel(sectionId) {
        const row = (aiConfig?.sections || []).find(s => s.id === sectionId);
        return row?.label || sectionId;
    }

    async function openAiBulk(sectionId) {
        if (!aiEnabled) {
            showToast('AI chưa bật (AI_ENABLED)', false);
            return;
        }
        await loadAiConfig();
        activeSectionId = sectionId;
        const jobs = collectJobs(sectionId);
        document.getElementById('langUiModal_ai_context').textContent =
            sectionLabel(sectionId) + ' — ' + jobs.length + ' trường · một request (Copy Prompt).';
        fillModelSelect(document.getElementById('langUiModal_ai_model'));
        openModal('ai');
    }

    document.getElementById('langUiModal_ai_run')?.addEventListener('click', async () => {
        if (!activeSectionId) return;
        const model = document.getElementById('langUiModal_ai_model').value;
        localStorage.setItem(MODEL_KEY, model);
        const debug = document.getElementById('langUiModal_ai_debug')?.checked;
        closeAllModals();
        try {
            await runSectionAiTranslate(activeSectionId, model, debug);
        } catch (e) {
            showToast(e.message, false);
        }
    });

    async function openGoogleBulk(sectionId) {
        await loadAiConfig();
        activeSectionId = sectionId;
        const jobs = collectJobs(sectionId);
        document.getElementById('langUiModal_google_context').textContent =
            sectionLabel(sectionId) + ' — Google Translate · ' + jobs.length + ' trường.';
        openModal('google');
    }

    document.getElementById('langUiModal_google_run')?.addEventListener('click', async () => {
        if (!activeSectionId) return;
        closeAllModals();
        const jobs = collectJobs(activeSectionId);
        await runSequential(activeSectionId, jobs, async job => {
            const json = await postJson(urls.google, {
                section_id: activeSectionId,
                key: job.key,
            });
            return json.data?.translated ?? '';
        });
    });

    async function openExport(sectionId) {
        await loadAiConfig();
        activeSectionId = sectionId;
        const json = await postJson(urls.export, { section_id: sectionId });
        document.getElementById('langUiModal_export_context').textContent =
            sectionLabel(sectionId) + ' — ' + (json.data?.key_count || 0) + ' key trong prompt.';
        document.getElementById('langUiModal_export_text').value = json.data?.prompt || '';
        openModal('export');
    }

    document.getElementById('langUiModal_export_copy')?.addEventListener('click', async () => {
        const t = document.getElementById('langUiModal_export_text');
        try {
            await navigator.clipboard.writeText(t.value);
            showToast('Đã sao chép prompt', true);
        } catch (e) {
            t.select();
            document.execCommand('copy');
            showToast('Đã sao chép', true);
        }
    });

    async function openImport(sectionId) {
        await loadAiConfig();
        activeSectionId = sectionId;
        document.getElementById('langUiModal_import_context').textContent =
            'Nhập JSON cho section: ' + sectionLabel(sectionId);
        document.getElementById('langUiModal_import_text').value = '';
        openModal('import');
    }

    document.getElementById('langUiModal_import_run')?.addEventListener('click', async () => {
        if (!activeSectionId) return;
        const pasted = document.getElementById('langUiModal_import_text').value;
        const { map: localMap, raw } = parseImportPayload(pasted);
        const requestBody = { section_id: activeSectionId };
        if (localMap) requestBody.payload_map = localMap;
        else requestBody.payload = raw;
        try {
            const json = await postJson(urls.import, requestBody);
            const n = applyImportMap(json.data?.translated || {});
            closeAllModals();
            const warnings = json.data?.warnings || [];
            if (warnings.length) {
                showToast(warnings[0] + ' Kiểm tra footer_legal trước khi lưu.', false);
            } else {
                showToast('Đã nhập ' + n + ' trường. Kiểm tra và lưu.', true);
            }
        } catch (e) {
            showToast(e.message, false);
        }
    });

    document.getElementById('langUiActions_ai_all')?.addEventListener('click', async () => {
        if (!aiEnabled) {
            showToast('AI chưa bật (AI_ENABLED)', false);
            return;
        }
        await loadAiConfig();
        const model = document.getElementById('langUiActions_ai_model')?.value;
        if (!model) {
            showToast('Chưa chọn mô hình AI', false);
            return;
        }
        localStorage.setItem(MODEL_KEY, model);
        const ids = listSectionIds();
        batchAbort = false;
        let ok = 0;
        let fail = 0;
        let skipped = 0;
        setGlobalBusy(true, '');

        for (let i = 0; i < ids.length; i++) {
            if (batchAbort) break;
            const sid = ids[i];
            setGlobalBusy(true, 'Section ' + (i + 1) + '/' + ids.length + ': ' + sectionLabel(sid));
            try {
                const res = await runSectionAiTranslate(sid, model, false, { autoSave: true, quiet: true });
                if (res.skipped) skipped++;
                else ok++;
            } catch (e) {
                fail++;
                console.error(sid, e);
            }
            if (i < ids.length - 1 && !batchAbort) {
                await new Promise(r => setTimeout(r, 400));
            }
        }

        setGlobalBusy(false);
        const msg = batchAbort
            ? 'Đã hủy — ' + ok + ' section đã dịch và lưu.'
            : ('Tất cả section: ' + ok + ' đã dịch và lưu' + (fail ? ', ' + fail + ' lỗi' : '') + (skipped ? ', ' + skipped + ' bỏ qua' : '') + '.');
        showToast(msg, !batchAbort && fail === 0);
    });

    loadAiConfig().then(() => {
        fillModelSelect(document.getElementById('langUiActions_ai_model'));
        fillModelSelect(document.getElementById('langUiModal_ai_model'));
    }).catch(() => {});
})();
</script>
@endpush
