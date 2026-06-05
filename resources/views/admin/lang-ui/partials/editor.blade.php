<div
    class="langUiEditor"
    id="langUiEditor"
    data-save-url="{{ $saveUrl }}"
    data-locale="{{ $locale }}"
    @if ($isMaster)
        data-ai-scope="{{ $scope }}"
        data-ai-config-url="{{ $aiConfigUrl }}"
        data-ai-enabled="{{ ($aiEnabled ?? false) ? '1' : '0' }}"
        data-ai-horizontal-url="{{ $aiTranslateHorizontalUrl ?? '' }}"
        data-horizontal-targets="{{ json_encode($horizontalTargetLocales ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) }}"
    @else
        data-ai-scope="{{ $scope }}"
        data-ai-config-url="{{ $aiConfigUrl }}"
        data-ai-enabled="{{ ($aiEnabled ?? false) ? '1' : '0' }}"
        data-ai-translate-url="{{ $aiTranslateUrl }}"
        data-ai-translate-section-url="{{ $aiTranslateSectionUrl }}"
        data-google-translate-url="{{ $googleTranslateUrl }}"
        data-export-prompt-url="{{ $exportPromptUrl }}"
        data-import-url="{{ $importUrl }}"
    @endif
>
    @foreach ($sections as $section)
        @foreach ($section['bundles'] as $bundle)
            @php
                $theme = $headerThemeResolver($section['id']);
                $descParts = [$bundle['key_count'] . ' trường'];
                if (! empty($section['anchor'])) {
                    $descParts[] = $section['anchor'];
                }
                if ($bundle['stem'] !== $section['id']) {
                    $descParts[] = $bundle['stem'] . '.php';
                }
            @endphp
            <section
                class="companyManagementPage_section companyManagementPage_section--tracked langUiSection"
                id="section-{{ $section['id'] }}"
                data-section-id="{{ $section['id'] }}"
                data-bundle="{{ $bundle['stem'] }}"
            >
                <div class="companyManagementPage_section_header companyManagementPage_section_header--{{ $theme }} langUiSection_header">
                    <div class="companyManagementPage_section_header_left">
                        <div class="companyManagementPage_section_header_iconWrapper companyManagementPage_section_header_iconWrapper--{{ $theme }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                @include('admin.lang-ui.partials.sectionIcon', ['sectionId' => $section['id']])
                            </svg>
                        </div>
                        <div class="companyManagementPage_section_header_info">
                            <h2 class="companyManagementPage_section_title">
                                {{ $section['label'] }}
                                @if (! empty($section['shared_landing']))
                                    <span class="langUiSection_badge langUiSection_badge--shared">Dùng chung landing</span>
                                @endif
                            </h2>
                            <p class="companyManagementPage_section_desc">{{ implode(' · ', $descParts) }}</p>
                        </div>
                    </div>
                    <div class="langUiSection_header_actions">
                        @if ($isMaster)
                            <div class="langUiSection_toolbar" data-lang-ui-horizontal-toolbar role="group" aria-label="Dịch ngang section sang mọi locale">
                                <button
                                    type="button"
                                    class="langUiSection_toolBtn langUiSection_toolBtn--horizontal {{ ($aiEnabled ?? false) ? '' : 'langUiSection_toolBtn--off' }}"
                                    data-action="horizontal-ai"
                                    title="{{ ($aiEnabled ?? false) ? 'AI: dịch section này sang mọi locale (tự lưu)' : 'AI chưa bật — cấu hình AI_ENABLED trong .env' }}"
                                    aria-label="Dịch ngang section bằng AI"
                                    @if (! ($aiEnabled ?? false)) disabled @endif
                                >
                                    <i class="fa-solid fa-arrows-left-right" aria-hidden="true"></i>
                                    <span class="langUiSection_toolBtn_text">Dịch ngang</span>
                                </button>
                            </div>
                            <span class="langUiSection_toolbar_divider" aria-hidden="true"></span>
                        @else
                            <div class="langUiSection_toolbar" data-lang-ui-translate-toolbar role="group" aria-label="Thao tác dịch section">
                                <button
                                    type="button"
                                    class="langUiSection_toolBtn langUiSection_toolBtn--ai {{ ($aiEnabled ?? false) ? '' : 'langUiSection_toolBtn--off' }}"
                                    data-action="ai-bulk"
                                    title="{{ ($aiEnabled ?? false) ? 'AI: prompt Copy Prompt → JSON → nhập form' : 'AI chưa bật — cấu hình AI_ENABLED trong .env' }}"
                                    aria-label="Dịch section bằng AI"
                                    @if (! ($aiEnabled ?? false)) disabled @endif
                                >
                                    <i class="fa-solid fa-wand-magic-sparkles" aria-hidden="true"></i>
                                    <span class="langUiSection_toolBtn_text">AI</span>
                                </button>
                                <button
                                    type="button"
                                    class="langUiSection_toolBtn langUiSection_toolBtn--google"
                                    data-action="google-bulk"
                                    title="Dịch hàng loạt bằng Google Translate"
                                    aria-label="Dịch hàng loạt Google"
                                >
                                    <i class="fa-brands fa-google" aria-hidden="true"></i>
                                    <span class="langUiSection_toolBtn_text">Google</span>
                                </button>
                                <button
                                    type="button"
                                    class="langUiSection_toolBtn langUiSection_toolBtn--export"
                                    data-action="export"
                                    title="Sao chép prompt dịch cả section (JSON)"
                                    aria-label="Copy Prompt"
                                >
                                    <i class="fa-solid fa-copy" aria-hidden="true"></i>
                                    <span class="langUiSection_toolBtn_text">Copy Prompt</span>
                                </button>
                                <button
                                    type="button"
                                    class="langUiSection_toolBtn langUiSection_toolBtn--import"
                                    data-action="import"
                                    title="Nhập JSON bản dịch vào form"
                                    aria-label="Nhập bản dịch"
                                >
                                    <i class="fa-solid fa-file-import" aria-hidden="true"></i>
                                    <span class="langUiSection_toolBtn_text">Nhập</span>
                                </button>
                            </div>
                            <span class="langUiSection_toolbar_divider" aria-hidden="true"></span>
                        @endif
                        <button
                            type="button"
                            class="adminButton adminButton--primary langUiSection_saveBtn"
                            data-bundle-save="{{ $bundle['stem'] }}"
                            data-section-id="{{ $section['id'] }}"
                        >
                            <svg class="langUiSection_saveBtn_icon langUiSection_saveBtn_icon--save" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H7.5a2.25 2.25 0 00-2.25 2.25v12.75a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25V6a2.25 2.25 0 00-2.25-2.25z"/>
                                <path d="M4.5 8.25h15M4.5 6h15"/>
                            </svg>
                            <svg class="langUiSection_saveBtn_icon langUiSection_saveBtn_icon--spin" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                            </svg>
                            <svg class="langUiSection_saveBtn_icon langUiSection_saveBtn_icon--done" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="langUiSection_saveBtn_text">Lưu {{ $section['label'] }}</span>
                        </button>
                        <button
                            type="button"
                            class="langUiSection_collapseBtn"
                            aria-expanded="true"
                            aria-controls="body-{{ $section['id'] }}-{{ $bundle['stem'] }}"
                            title="Thu gọn / mở rộng"
                        >
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="langUiSection_collapseBtn_icon" aria-hidden="true">
                                <path d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </button>
                    </div>
                </div>

                @if (! empty($section['note']))
                    <div class="langUiSection_notice" role="note">
                        <i class="fa-solid fa-circle-info" aria-hidden="true"></i>
                        <span>{{ $section['note'] }}</span>
                    </div>
                @endif

                <div class="langUiSection_workzone">
                    <div class="langUiSection_lock" data-lang-ui-section-lock hidden aria-hidden="true">
                        <div class="langUiSection_lock_inner">
                            <i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i>
                            <p class="langUiSection_lock_text" data-lang-ui-lock-status>Đang dịch…</p>
                            <button type="button" class="adminButton adminButton--secondary langUiSection_lock_cancel" data-lang-ui-lock-cancel>Hủy</button>
                        </div>
                    </div>

                    <div class="companyManagementPage_section_body langUiSection_body" id="body-{{ $section['id'] }}-{{ $bundle['stem'] }}">
                        @foreach ($bundle['fields'] as $field)
                            <div class="langUiField" data-key="{{ $field['key'] }}" data-section-id="{{ $section['id'] }}">
                                <label class="langUiField_label" for="f-{{ $bundle['stem'] }}-{{ $field['key'] }}">
                                    <code>{{ $field['key'] }}</code>
                                    @if ($field['is_html'])
                                        <span class="langUiField_tag">HTML</span>
                                    @endif
                                </label>
                                <div class="langUiField_row {{ $isMaster ? 'langUiField_row--single' : '' }}" data-lang-ui-row>
                                    @unless ($isMaster)
                                        <div class="langUiField_col langUiField_col--ref">
                                            <textarea
                                                class="langUiField_textarea langUiField_textarea--ref"
                                                readonly
                                                tabindex="-1"
                                                aria-readonly="true"
                                                data-lang-ui-ref
                                            >{{ $field['vi'] }}</textarea>
                                        </div>
                                    @endunless
                                    <div class="langUiField_col langUiField_col--edit">
                                        <textarea
                                            id="f-{{ $bundle['stem'] }}-{{ $field['key'] }}"
                                            name="keys[{{ $field['key'] }}]"
                                            class="langUiField_textarea langUiField_textarea--edit"
                                            data-bundle-input="{{ $bundle['stem'] }}"
                                            data-lang-ui-edit
                                        >{{ $field['value'] }}</textarea>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endforeach
    @endforeach
</div>

<div class="langUiToast" id="langUiToast" role="status" aria-live="polite" hidden></div>
