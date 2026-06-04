@php
    $masterCode = $masterLocale ?? \App\Support\LangUi::MASTER_LOCALE;
    $isMaster = $isMaster ?? ($locale === $masterCode);
    $localeEditRoute = $localeEditRoute ?? 'admin.lang-ui.edit';
    $previewLabel = $previewLabel ?? 'Xem trang';
@endphp

<div class="langUiBanner {{ $isMaster ? 'langUiBanner--origin' : 'langUiBanner--mode' }}">
    <div class="langUiBanner_inner">
        <div class="langUiBanner_top">
            <div class="langUiBanner_left">
                <i class="fa-solid fa-language langUiBanner_icon" aria-hidden="true"></i>
                <div class="langUiBanner_text">
                    <div class="langUiBanner_title">
                        @if ($isMaster)
                            <span>Đang chỉnh sửa bản gốc:</span>
                        @else
                            <span>Đang chỉnh sửa bản dịch:</span>
                        @endif
                        @if (! empty($currentLang['flag']))
                            <img src="{{ $currentLang['flag'] }}" alt="{{ $locale }}" class="langUiFlag langUiFlag--lg">
                        @endif
                        <strong>{{ strtoupper($locale) }} — {{ $currentLang['name_native'] ?? $locale }}</strong>
                    </div>
                    <div class="langUiBanner_subtext">
                        @if ($isMaster)
                            Tiếng Việt là <strong>chuẩn tham chiếu</strong> cho mọi locale khác.
                        @else
                            Cột <span class="langUiBanner_legend langUiBanner_legend--ref">xám</span> = tiếng Việt (chỉ đọc).
                            Cột <span class="langUiBanner_legend langUiBanner_legend--edit">vàng</span> = bản dịch — chỉnh và lưu từng section.
                        @endif
                    </div>
                </div>
            </div>
            <a href="{{ $publicPreviewUrl }}" class="langUiBanner_btnPreview" target="_blank" rel="noopener">
                <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i>
                <span>{{ $previewLabel }}</span>
            </a>
        </div>

        <div class="langUiBanner_bottom">
            <div class="langUiBanner_switcher">
                <span class="langUiBanner_switcherLabel">Phiên bản</span>
                <div class="langUiBanner_switcherList" role="group" aria-label="Chọn ngôn ngữ">
                    @foreach ($languages as $code => $lang)
                        @php
                            $hasTrans = $statusMap[$code] ?? false;
                            $active = $locale === $code;
                            $stateClass = $active
                                ? 'is-active'
                                : ($hasTrans ? 'has-translation' : 'no-translation');
                        @endphp
                        <a href="{{ route($localeEditRoute, ['locale' => $code]) }}"
                           class="langUiBanner_switcherItem {{ $stateClass }}"
                           title="{{ $lang['name_vi'] ?: $lang['name_native'] }} — {{ $hasTrans ? 'Đã có nội dung' : 'Chưa đủ / mới' }}">
                            @if (! empty($lang['flag']))
                                <img src="{{ $lang['flag'] }}" alt="" class="langUiFlag langUiFlag--sm">
                            @else
                                <span class="langUiFlag langUiFlag--text">{{ $lang['code_display'] ?? strtoupper($code) }}</span>
                            @endif
                            <span class="langUiBanner_switcherCode">{{ $lang['code_display'] ?? strtoupper($code) }}</span>
                            @if ($hasTrans && ! $active)
                                <i class="fa-solid fa-check langUiBanner_switcherCheck" aria-hidden="true"></i>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>

            @unless ($isMaster)
                <div class="langUiActions" id="langUiActions" data-lang-ui-actions>
                    <span class="langUiActions_label">Hành động</span>
                    <div class="langUiActions_row">
                        <label class="langUiActions_field" for="langUiActions_ai_model">
                            <span class="langUiActions_fieldLabel">Mô hình AI</span>
                            <select id="langUiActions_ai_model" class="langUiActions_select" disabled>
                                <option value="">Đang tải…</option>
                            </select>
                        </label>
                        <button
                            type="button"
                            class="langUiActions_btn langUiActions_btn--primary"
                            id="langUiActions_ai_all"
                            @if (! ($aiEnabled ?? false)) disabled @endif
                            title="{{ ($aiEnabled ?? false) ? 'Dịch tuần tự mọi section (prompt Copy Prompt)' : 'Bật AI_ENABLED trong .env' }}"
                        >
                            <i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i>
                            Dịch tất cả (AI)
                        </button>
                        <button
                            type="button"
                            class="langUiActions_btn langUiActions_btn--ghost"
                            id="langUiActions_cancel"
                            hidden
                            data-lang-ui-global-cancel
                        >
                            Hủy
                        </button>
                    </div>
                    <p class="langUiActions_hint">Dịch tất cả: mỗi section được AI dịch (prompt Copy Prompt), nhập form và <strong>tự lưu</strong> file.</p>
                </div>
            @endunless
        </div>
    </div>
</div>
