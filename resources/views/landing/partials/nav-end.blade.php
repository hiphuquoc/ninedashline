  <div class="nav-end">
    <div class="nav-actions" role="toolbar" aria-label="{{ $navLangUi['lang_trigger'] ?? t('lang_trigger') }}">
      <div class="lang-switcher" data-lang-switcher data-current-locale="{{ $navDefaultLocale }}">
        <div class="lang-switcher__backdrop" data-lang-backdrop aria-hidden="true"></div>
        <button type="button" class="nav-action lang-switcher__trigger" aria-haspopup="dialog" aria-expanded="false"
                data-nav-ui="lang_trigger" aria-label="{{ $navLangUi['lang_trigger'] }}">
          <img class="lang-switcher__flag" id="langTriggerFlag" src="{{ $imgPlaceholder }}" data-src="{{ $navLanguages[$navDefaultLocale]['flag'] ?? '' }}" data-priority="high" width="22" height="22" alt="{{ $navLanguages[$navDefaultLocale]['name_native'] ?? '' }}" decoding="async" />
          <span class="lang-switcher__code" id="langTriggerCode">{{ $navLanguages[$navDefaultLocale]['code_short'] ?? 'EN' }}</span>
          <svg class="lang-switcher__chevron" viewBox="0 0 12 12" fill="none" aria-hidden="true">
            <path d="M2.5 4.5L6 8l3.5-3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
        </button>
        <div class="lang-switcher__menu" role="dialog" aria-modal="true" aria-labelledby="langDialogTitle" hidden>
          <div class="lang-switcher__mobileHeader">
            <div class="lang-switcher__mobileHeader_title" id="langDialogTitle" data-lang-ui="dialog_title">{{ $navLangUi['dialog_title'] }}</div>
            <button type="button" class="lang-switcher__mobileHeader_close" data-lang-cancel aria-label="{{ $navLangUi['close'] }}">
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" aria-hidden="true">
                <path d="M2 2l10 10M12 2L2 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </button>
          </div>
          <div class="lang-switcher__head" aria-label="{{ $navLangUi['language'] }}">
            <span class="lang-switcher__col_title" data-lang-ui="language">{{ $navLangUi['language'] }}</span>
            <span class="lang-switcher__col_sub" data-lang-ui="display_hint">{{ $navLangUi['display_hint'] ?? '' }}</span>
          </div>
          <div class="lang-switcher__searchWrap">
            <input type="search" class="lang-switcher__search" data-lang-search autocomplete="off"
                   placeholder="{{ $navLangUi['search_placeholder'] }}" aria-label="{{ $navLangUi['search_placeholder'] }}" />
          </div>
          <div class="lang-switcher__body">
            <div class="lang-switcher__list" role="group" data-lang-col="list">
              @foreach($navLanguages as $code => $lang)
                @php $isCurrent = $code === $navDefaultLocale; @endphp
                <button type="button"
                        class="lang-switcher__item{{ $isCurrent ? ' is-selected is-current' : '' }}"
                        role="menuitemradio"
                        aria-checked="{{ $isCurrent ? 'true' : 'false' }}"
                        data-lang-code="{{ $code }}"
                        data-lang-url="{{ \App\Support\LocaleUrl::home($code) }}"
                        data-search="{{ $lang['search'] }}"
                        title="{{ $lang['name_native'] }}">
                  <img class="lang-switcher__item_flag" src="{{ $imgPlaceholder }}" data-src="{{ $lang['flag'] }}" data-flag-lazy width="28" height="28" alt="{{ $lang['name_native'] }}" decoding="async" />
                  <span class="lang-switcher__item_text">
                    <span class="lang-switcher__item_title">{{ $lang['name_native'] }}</span>
                    <span class="lang-switcher__item_sub">{{ $lang['code_display'] }}</span>
                  </span>
                  <span class="lang-switcher__item_check" aria-hidden="true">✓</span>
                </button>
              @endforeach
            </div>
            <p class="lang-switcher__empty" data-lang-empty data-lang-ui="no_results">{{ $navLangUi['no_results'] }}</p>
          </div>
          <footer class="lang-switcher__footer">
            <button type="button" class="lang-switcher__btn lang-switcher__btn--ghost" data-lang-cancel data-lang-ui="cancel">{{ $navLangUi['cancel'] }}</button>
            <button type="button" class="lang-switcher__btn lang-switcher__btn--primary" data-lang-apply><span data-lang-ui="apply">{{ $navLangUi['apply'] }}</span></button>
          </footer>
        </div>
      </div>

      <button type="button" class="nav-action nav-action--sound" id="soundBtn" aria-pressed="false"
              data-nav-ui="sound_off" aria-label="{{ $navLangUi['sound_off'] }}">
        <span class="nav-action__icon nav-action__icon--sound" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
            <g class="sound-icon-waves">
              <path d="M11 5L6 9H3v6h3l5 4V5z"/>
              <path d="M15.5 8.5a5 5 0 010 7"/>
              <path d="M18 6a8 8 0 010 12"/>
            </g>
            <path class="sound-icon-slash" d="M4 4l16 16"/>
          </svg>
        </span>
      </button>

      <a href="#sovereignty" class="nav-action nav-action--share" id="shareBtn" data-cursor aria-label="{{ $navLangUi['nav_share'] ?? t('nav_share') }}">
        <span class="nav-action__icon" aria-hidden="true">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><path d="M8.6 13.5l6.8 4M15.4 6.5l-6.8 4"/></svg>
        </span>
        <span class="nav-action__label" data-nav-label="nav_share">{{ $navLangUi['nav_share'] ?? t('nav_share') }}</span>
      </a>
    </div>
  </div>
