{{-- Nhập JSON section — dùng chung bản gốc (vi) và bản dịch --}}
<div class="langUiModal" id="langUiModal_import" role="dialog" aria-modal="true" hidden>
    <div class="langUiModal_backdrop" data-lang-ui-modal-close></div>
    <div class="langUiModal_dialog langUiModal_dialog--wide">
        <header class="langUiModal_header">
            <h2 class="langUiModal_title"><i class="fa-solid fa-file-import"></i> Nhập JSON section</h2>
            <button type="button" class="langUiModal_close" data-lang-ui-modal-close aria-label="Đóng"><i class="fa-solid fa-xmark"></i></button>
        </header>
        <div class="langUiModal_body">
            <p class="langUiModal_lead" id="langUiModal_import_context"></p>
            <p class="langUiModal_hint">JSON object <code>{"key":"value"}</code> hoặc mảng <code>[{"key":"…","value":"…"}]</code>.
                Với HTML (<code>footer_legal</code>, …): trong JSON dùng <code>\"</code>; sau khi áp dụng, ô nhập hiển thị <code>"</code> (không còn <code>\</code>) — đúng HTML để lưu.</p>
            <textarea id="langUiModal_import_text" class="langUiModal_textarea" rows="14" spellcheck="false" placeholder='{"hero_title":"…","hero_sub":"…"}'></textarea>
        </div>
        <footer class="langUiModal_footer">
            <button type="button" class="adminButton adminButton--secondary" data-lang-ui-modal-close>Hủy</button>
            <button type="button" class="adminButton adminButton--primary" id="langUiModal_import_run">Áp dụng vào form</button>
        </footer>
    </div>
</div>
