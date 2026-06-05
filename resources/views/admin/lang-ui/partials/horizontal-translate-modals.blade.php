@if ($isMaster ?? false)
{{-- Dịch ngang: xác nhận --}}
<div class="langUiModal" id="langUiModal_horizontal" role="dialog" aria-modal="true" hidden>
    <div class="langUiModal_backdrop" data-lang-ui-modal-close></div>
    <div class="langUiModal_dialog">
        <header class="langUiModal_header">
            <h2 class="langUiModal_title"><i class="fa-solid fa-arrows-left-right"></i> Dịch ngang — AI</h2>
            <button type="button" class="langUiModal_close" data-lang-ui-modal-close aria-label="Đóng"><i class="fa-solid fa-xmark"></i></button>
        </header>
        <div class="langUiModal_body">
            <p class="langUiModal_lead" id="langUiModal_horizontal_context"></p>
            <p class="langUiModal_hint">Dịch <strong>cả section</strong> từ bản gốc tiếng Việt sang <strong>mọi locale</strong> (prompt Copy Prompt), <strong>tự lưu</strong> từng file. Tiến trình và báo cáo hiển thị theo thời gian thực.</p>
            <label class="langUiModal_label" for="langUiModal_horizontal_model">Mô hình</label>
            <select id="langUiModal_horizontal_model" class="langUiModal_select"></select>
            <label class="langUiModal_debug">
                <input type="checkbox" id="langUiModal_horizontal_debug" /> Log debug (Console)
            </label>
        </div>
        <footer class="langUiModal_footer">
            <button type="button" class="adminButton adminButton--secondary" data-lang-ui-modal-close>Hủy</button>
            <button type="button" class="adminButton adminButton--primary" id="langUiModal_horizontal_run"><i class="fa-solid fa-play"></i> Bắt đầu dịch ngang</button>
        </footer>
    </div>
</div>

{{-- Dịch ngang: tiến trình + báo cáo --}}
<div class="langUiModal" id="langUiModal_horizontal_progress" role="dialog" aria-modal="true" hidden>
    <div class="langUiModal_backdrop"></div>
    <div class="langUiModal_dialog langUiModal_dialog--wide">
        <header class="langUiModal_header">
            <h2 class="langUiModal_title" id="langUiModal_horizontal_progress_title"><i class="fa-solid fa-spinner fa-spin"></i> Đang dịch ngang…</h2>
            <button type="button" class="langUiModal_close" id="langUiModal_horizontal_progress_close" hidden aria-label="Đóng"><i class="fa-solid fa-xmark"></i></button>
        </header>
        <div class="langUiModal_body">
            <p class="langUiModal_lead" id="langUiModal_horizontal_progress_context"></p>
            <div class="langUiHorizontal_progress">
                <div class="langUiHorizontal_progressBar" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                    <div class="langUiHorizontal_progressFill" id="langUiModal_horizontal_progress_fill" style="width:0%"></div>
                </div>
                <p class="langUiHorizontal_status" id="langUiModal_horizontal_status" aria-live="polite">Chuẩn bị…</p>
            </div>
            <div class="langUiHorizontal_report" id="langUiModal_horizontal_report" hidden>
                <div class="langUiHorizontal_summary" id="langUiModal_horizontal_summary"></div>
                <div class="langUiHorizontal_tableWrap">
                    <table class="langUiHorizontal_table" id="langUiModal_horizontal_table">
                        <thead>
                            <tr>
                                <th scope="col">Locale</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Trường</th>
                                <th scope="col">Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody id="langUiModal_horizontal_tbody"></tbody>
                    </table>
                </div>
            </div>
        </div>
        <footer class="langUiModal_footer">
            <button type="button" class="adminButton adminButton--secondary" id="langUiModal_horizontal_cancel" data-lang-ui-horizontal-cancel>Hủy</button>
            <button type="button" class="adminButton adminButton--primary" id="langUiModal_horizontal_done" hidden>Đóng báo cáo</button>
        </footer>
    </div>
</div>
@endif
