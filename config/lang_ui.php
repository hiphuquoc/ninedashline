<?php

/**
 * UI landing — chuỗi đa ngôn ngữ.
 *
 * KHÔNG gộp locale tại đây (tránh config:cache / optimize làm site mất chuỗi hoặc OOM).
 * AppServiceProvider nạp LangUi::all() lúc boot — xem App\Support\LangUi.
 *
 * Blade: {{ t('hero_title_line1') }} · config/lang_ui/{locale}/*.php
 */
return [];
