@unless ($isMaster ?? true)
{{-- AI bulk (cả section — prompt Copy Prompt) --}}
<div class="langUiModal" id="langUiModal_ai" role="dialog" aria-modal="true" hidden>
    <div class="langUiModal_backdrop" data-lang-ui-modal-close></div>
    <div class="langUiModal_dialog">
        <header class="langUiModal_header">
            <h2 class="langUiModal_title"><i class="fa-solid fa-wand-magic-sparkles"></i> Dịch section — AI</h2>
            <button type="button" class="langUiModal_close" data-lang-ui-modal-close aria-label="Đóng"><i class="fa-solid fa-xmark"></i></button>
        </header>
        <div class="langUiModal_body">
            <p class="langUiModal_lead" id="langUiModal_ai_context"></p>
            <p class="langUiModal_hint">Một request AI dùng <strong>cùng prompt Copy Prompt</strong> (JSON cả section), sau đó tự nhập vào form — tương đương Copy Prompt + Nhập.</p>
            <label class="langUiModal_label" for="langUiModal_ai_model">Mô hình</label>
            <select id="langUiModal_ai_model" class="langUiModal_select"></select>
            <label class="langUiModal_debug">
                <input type="checkbox" id="langUiModal_ai_debug" /> Log prompt (Console)
            </label>
        </div>
        <footer class="langUiModal_footer">
            <button type="button" class="adminButton adminButton--secondary" data-lang-ui-modal-close>Hủy</button>
            <button type="button" class="adminButton adminButton--primary" id="langUiModal_ai_run"><i class="fa-solid fa-play"></i> Bắt đầu dịch</button>
        </footer>
    </div>
</div>

{{-- Google bulk confirm --}}
<div class="langUiModal" id="langUiModal_google" role="dialog" aria-modal="true" hidden>
    <div class="langUiModal_backdrop" data-lang-ui-modal-close></div>
    <div class="langUiModal_dialog">
        <header class="langUiModal_header">
            <h2 class="langUiModal_title"><i class="fa-brands fa-google"></i> Dịch hàng loạt — Google</h2>
            <button type="button" class="langUiModal_close" data-lang-ui-modal-close aria-label="Đóng"><i class="fa-solid fa-xmark"></i></button>
        </header>
        <div class="langUiModal_body">
            <p class="langUiModal_lead" id="langUiModal_google_context"></p>
            <p class="langUiModal_hint">Google Translate (gtx) qua <code>scripts/translation-text-utils.php</code> — giữ HTML và placeholder. Không ghi đè file trên disk cho đến khi bạn bấm Lưu section.</p>
        </div>
        <footer class="langUiModal_footer">
            <button type="button" class="adminButton adminButton--secondary" data-lang-ui-modal-close>Hủy</button>
            <button type="button" class="adminButton adminButton--primary" id="langUiModal_google_run">Bắt đầu dịch</button>
        </footer>
    </div>
</div>

{{-- Copy Prompt --}}
<div class="langUiModal" id="langUiModal_export" role="dialog" aria-modal="true" hidden>
    <div class="langUiModal_backdrop" data-lang-ui-modal-close></div>
    <div class="langUiModal_dialog langUiModal_dialog--wide">
        <header class="langUiModal_header">
            <h2 class="langUiModal_title"><i class="fa-solid fa-copy"></i> Copy Prompt</h2>
            <button type="button" class="langUiModal_close" data-lang-ui-modal-close aria-label="Đóng"><i class="fa-solid fa-xmark"></i></button>
        </header>
        <div class="langUiModal_body">
            <p class="langUiModal_lead" id="langUiModal_export_context"></p>
            <p class="langUiModal_hint">Dán vào ChatGPT/Claude… Yêu cầu AI trả về <strong>JSON object</strong> cùng key (có escape <code>\"</code> cho dấu <code>"</code> trong HTML). Sau đó dùng 「Nhập」 hoặc nút AI trên section.</p>
            <textarea id="langUiModal_export_text" class="langUiModal_textarea" rows="18" readonly></textarea>
        </div>
        <footer class="langUiModal_footer">
            <button type="button" class="adminButton adminButton--secondary" data-lang-ui-modal-close>Đóng</button>
            <button type="button" class="adminButton adminButton--primary" id="langUiModal_export_copy"><i class="fa-solid fa-copy"></i> Sao chép</button>
        </footer>
    </div>
</div>

{{-- Import --}}
<div class="langUiModal" id="langUiModal_import" role="dialog" aria-modal="true" hidden>
    <div class="langUiModal_backdrop" data-lang-ui-modal-close></div>
    <div class="langUiModal_dialog langUiModal_dialog--wide">
        <header class="langUiModal_header">
            <h2 class="langUiModal_title"><i class="fa-solid fa-file-import"></i> Nhập bản dịch</h2>
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
@endunless
