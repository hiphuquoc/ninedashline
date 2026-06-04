# Quy chuẩn dịch — Landing Hoàng Sa · Trường Sa

## 1. Mục tiêu

Bản dịch phải:

- **Nhất quán** với bản tiếng Việt (`vi`) về nội dung pháp lý, lịch sử và quan điểm chủ quyền Việt Nam.
- **Đúng & đủ**: không lược bỏ luận điểm, không thêm ý không có trong `vi`.
- **Rõ nghĩa**: câu ngắn gọn, mạch lạc; tránh văn phong máy dịch word-by-word.
- **Chuẩn ngữ pháp** theo chuẩn mực của ngôn ngữ đích (giáo dục, trang thông tin công cộng).
- **Thông dụng địa phương**: dùng từ ngữ tự nhiên với người bản xứ (không Hán-Việt thừa với en; không giản thể với zh-tw).

## 2. Nguồn & thứ tự làm việc

| Ưu tiên | Nguồn | Vai trò |
|---------|--------|---------|
| 1 | `config/lang_ui/vi/` | **Master** — sự thật về ý nghĩa |
| 2 | `docs/translation/geo-names.json` | Tên địa danh theo locale (`vietnam`, `hoang_sa`, `truong_sa`, `combined`, `nine_dash_line`, `china`) — dùng chung hoangsa.dev |
| 3 | `docs/translation/glossary.md` | Thuật ngữ khác (Biển Đông, chủ quyền, …) |
| 4 | `config/lang_ui/en/` | Tham khảo cấu trúc câu, tone quốc tế |
| 5 | Bản dịch locale đang sửa | Chỉ sửa khi review |

**Không** dịch từ tiếng Anh sang ngôn ngữ khác mà bỏ qua đối chiếu `vi`.

## 3. Giọng văn (tone)

- Trang **phi lợi nhuận, giáo dục**, tri ân lịch sử, kêu gọi **đấu tranh hòa bình**.
- Trang trọng, có cảm xúc vừa phải; **không** kích động thù hận dân tộc, **không** tấn công cá nhân.
- Với độc giả Trung Quốc (`voice_3_*`): mời **lắng nghe đa chiều**, không mỉa mai dân tộc.
- Tránh tuyên ngôn quá cứng nhắc kiểu nhà nước; ưu tiên “chúng tôi / các bạn / độc giả”.

## 4. Quy tắc kỹ thuật (bắt buộc)

### 4.1 File PHP

```php
<?php

return [
    'key_name' => 'Chuỗi dịch',
];
```

- **Mỗi key** trong `vi` phải có **đúng một** bản dịch cùng tên key.
- Không đổi tên key, không gộp/tách key.
- Chuỗi dùng nháy đơn `'...'`; escape `\'` trong nội dung.
- Giữ `array (` hoặc `[` như file `vi` tương ứng (thống nhất theo từng file).

### 4.2 HTML trong chuỗi

- Giữ **đủ** thẻ HTML như bản `vi` / `en`.
- Không bọc thêm `<p>` trừ khi key đã có (vd. `action_bridge`).
- Class CSS: `accent`, `red-accent` — không đổi.

### 4.3 Placeholder

| Key pattern | Giữ nguyên |
|-------------|------------|
| `:year`, `:hoangsa`, `:truongsa`, `:count` | Tên biến |
| URL trong `footer_legal`, `action_bridge` | href đầy đủ |

### 4.4 Độ dài & UI

- `nav_*`, `*_label`, nút CTA: ngắn, vừa khung UI.
- `*_html`, `*_thesis`, `mail_*_body`: đủ ý, có thể dài hơn `vi` ~10–15% nếu ngữ pháp bắt buộc.
- `display_hint` → `:count languages` / tương đương (số nhiều đúng ngữ pháp).

### 4.5 Tên đảo & quốc gia (Hoàng Sa · Trường Sa · Việt Nam)

- Tra **`geo-names.json`** theo mã locale (`vietnam`, `hoang_sa`, `truong_sa`, `combined`, `nine_dash_line`, `china`).
- Hero: **một key** `hero_title_country` = `vietnam` trong JSON — **không** tách `hero_title_vn` / `hero_title_nam` (MT dễ thành hai từ vô nghĩa).
- Sau khi sửa JSON: khối lệnh trong [geo-names.md](./geo-names.md#lệnh-chạy-copy-một-lượt--wsl).
- `vi`: luôn **Hoàng Sa**, **Trường Sa**, **Việt Nam**.
- Locale khác: dùng đúng chuỗi trong JSON (vd. en *Paracel Islands* / *Spratly Islands*), không roman hóa `Hoang Sa` trừ trích dẫn lịch sử hoặc logo/URL.
- Trích dẫn đối phương (bản đồ, khẩu hiệu, văn bản nước ngoài): giữ tên trong nguồn; phần lời site dùng map locale.

### 4.6 Không dịch / giữ nguyên

- Tên riêng lịch sử: San Francisco, UNCLOS, Gia Long, Minh Mạng, Geneva (có thể thêm phiên âm địa phương lần đầu nếu cần).
- Facebook, Twitter, Email (hoặc bản địa hóa tên mạng nếu phổ biến: 微博 không thay Twitter trừ khi đổi cả UX).
- Số liệu: 121, 142, 300+, 1974, 46–3.

## 5. Nội dung nhạy cảm

- Khẳng định: hai quần đảo (tên theo `geo-names.json` của locale) thuộc **Việt Nam**; chiếm đóng **1974** là **vũ lực**, trái luật quốc tế.
- Trung Quốc: dùng `china` trong JSON (vd. vi *Trung Quốc*, zh-cn *中国*); mô tả hành vi “chiếm đóng bằng vũ lực”, không dùng từ xúc phạm.
- Không viết như thể tranh chấp “ngang hàng không rõ ràng” — bản `vi` đã khẳng định lập trường.

## 6. Kiểm tra trước khi merge

- [ ] `php scripts/validate-lang-ui.php {locale}` — 0 key thiếu/thừa.
- [ ] Đọc lướt trên `/ {locale}` toàn section: hero → footer.
- [ ] Mailto subject/body mở đúng, ký tự UTF-8.
- [ ] Không lộ chuỗi tiếng Việt/Anh còn sót (trừ tên riêng quốc tế).
- [ ] Tên đảo khớp `geo-names.json`; thuật ngữ khác khớp `glossary.md`.

## 7. Thứ tự ưu tiên locale (đề xuất)

1. **en** — xong  
2. **zh-cn**, **zh-tw** — đông độc giả; tên đảo theo `geo-names.json` (西沙/南沙)  
3. **ja**, **ko**, **th** — khu vực  
4. **fr**, **de**, **es**, **ru**, **ar** — quốc tế  
5. Các locale còn lại trong `config/language.php` — lần lượt theo `status.md`

## 8. Sau khi hoàn thành một locale

1. Cập nhật `docs/translation/status.md` → ✅  
2. `php scripts/validate-lang-ui.php {code}` — locale tự vào `LangUi::contentLocales()`  
3. Commit message mẫu: `i18n(zh-cn): add landing UI translations`

## 9. Chạy lại pipeline (scripts)

→ **[LANG-UI-WORKFLOW.md](./LANG-UI-WORKFLOW.md)** (sửa chữ ở đâu, lô batch, caption timeline, đầy đủ lệnh PHP).
