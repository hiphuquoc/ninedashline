# Prompt dịch AI (copy-paste)

Thay `{LOCALE}`, `{LANG_NAME}`, `{LANG_NATIVE}` trước khi gửi cho AI.

**Tên địa danh:** mở `docs/translation/geo-names.json` (bản 2026-05, dùng chung hoangsa.dev) → object `{LOCALE}` → dùng **6 key**: `vietnam`, `hoang_sa`, `truong_sa`, `combined`, `nine_dash_line`, `china`.

---

## Prompt chính

```
Bạn là biên dịch chuyên trang web giáo dục lịch sử–pháp lý về chủ quyền biển đảo Việt Nam.

NHIỆM VỤ
Dịch toàn bộ file config PHP landing từ tiếng Việt (master) sang {LANG_NAME} ({LOCALE}).
Đầu ra: 14 file PHP landing (+ footer riêng) trong config/lang_ui/{LOCALE}/ với ĐÚNG mọi key như bản vi.

NGUỒN (đính kèm hoặc paste từ repo)
- Master: config/lang_ui/vi/*.php — 14 bundle: scripts/landing-lang-files.php
- Tên địa danh: docs/translation/geo-names.json → key "{LOCALE}" (vietnam, hoang_sa, truong_sa, combined, nine_dash_line, china)
- Thuật ngữ khác: docs/translation/glossary.md
- Quy chuẩn: docs/translation/guide.md
- Tham khảo: config/lang_ui/en/*.php

TÊN ĐỊA DANH ({LOCALE})
- vietnam: [paste từ geo-names.json] → dùng cho key hero_title_country (MỘT chuỗi, không tách vn/nam)
- hoang_sa: [paste từ geo-names.json]
- truong_sa: [paste từ geo-names.json]
- combined: [paste từ geo-names.json]
- nine_dash_line: [paste từ geo-names.json]
- china: [paste từ geo-names.json]
Không dùng hero_title_vn / hero_title_nam (đã bỏ — MT từng nửa dễ sai).

QUY TẮC BẮT BUỘC
1. Không đổi tên key; không thiếu key; không thêm key.
2. Giữ HTML: <em>, <strong>, <br>, <span class="accent">, <span class="red-accent">.
3. Giữ placeholder :year, :hoangsa, :truongsa, :count.
4. Giọng: trang trọng, giáo dục, hòa bình; đủ luận điểm pháp lý như bản vi; ngữ pháp chuẩn {LANG_NATIVE}.
5. Format PHP: return [ 'key' => 'value', ]; nháy đơn, escape \' đúng.
6. mail_*_body: giữ cấu trúc đoạn và bullet như vi.

ĐỊNH DẠNG TRẢ LỜI
- Xuất lần lượt 14 bundle: nav, meta, hero, mapview, timeline, evidence, beauty, history, action, situation, rulings, faq, sources, memorial (footer.php pipeline riêng).
- Mỗi file là khối code PHP hoàn chỉnh, sẵn sàng lưu disk.

TỰ KIỂM TRA
- Đếm key mỗi file phải khớp vi.
- Không còn tiếng Việt trong value (trừ tên riêng quốc tế trong trích dẫn).
- Tên đảo khớp geo-names.json cho {LOCALE}.
```

---

## Prompt rút gọn (một file)

```
Dịch file config/lang_ui/vi/{FILE}.php sang {LANG_NAME} ({LOCALE}).
Tuân thủ docs/translation/guide.md, geo-names.json ({LOCALE}), glossary.md.
Giữ nguyên mọi key và HTML. Output: PHP return array đầy đủ.
[Paste vietnam / hoang_sa / truong_sa / combined / nine_dash_line / china từ geo-names.json]
[Paste nội dung vi/{FILE}.php]
```

Sau khi sửa master `vi` trên disk:

- **Trong admin:** `/he-thong/ngon-ngu/{locale}` → nút **Copy** (prompt đầy đủ) hoặc **AI** / **Google** theo section.
- **Ngoài admin:** dùng prompt ở trên, lưu `config/lang_ui/{locale}/`, rồi `php artisan config:clear`.

CLI `translate.php` / `translate-section.php` đã gỡ — tránh dịch nhầm toàn site.

---

## Ví dụ thay biến

| Locale | LANG_NAME | LANG_NATIVE | hoang_sa (tra JSON) |
|--------|-----------|-------------|---------------------|
| zh-cn | Chinese (Simplified) | 简体中文 | 西沙群岛 |
| zh-tw | Chinese (Traditional) | 繁體中文 | 西沙群島 |
| ja | Japanese | 日本語 | 西沙諸島 |
| ko | Korean | 한국어 | 파라셀 제도 |
| en | English | English | Paracel Islands |
| th | Thai | ภาษาไทย | หมู่เกาะพาราเซล |
| it | Italian | italiano | Isole Paracel |
| fr | French | français | Îles Paracels |
| de | German | Deutsch | Paracel-Inseln |
| es | Spanish | español | Islas Paracel |
