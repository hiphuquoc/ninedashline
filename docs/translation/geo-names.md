# Tên địa danh Hoàng Sa · Trường Sa

**Nguồn chuẩn (machine-readable):** [`geo-names.json`](./geo-names.json) — bản **2026-05**, **50 locale** (đồng bộ `config/language.php`).

**Dùng chung:** `hoangsa.dev` và `ninedashline.dev` — cùng một file JSON; cập nhật bằng `php scripts/publish-geo-names-json.php` (trong `ninedashline.dev`) hoặc copy thủ công sang cả hai repo.

## Nguyên tắc

| Locale | Cách gọi | Ví dụ |
|--------|----------|-------|
| `vi` | Tên Việt Nam chính thức | Hoàng Sa, Trường Sa |
| Còn lại | Tên địa lý quốc tế / bản địa hóa trong `geo-names.json` | en: Paracel Islands, Spratly Islands · zh-cn: 西沙群岛, 南沙群岛 |

- **Không** tự dịch hoặc trộn tên (vd. `Hoang Sa` trong bản `en` khi map đã ghi `Paracel Islands`).
- **Không** đổi thứ tự: luôn Hoàng Sa (Paracel) trước, Trường Sa (Spratly) sau — trừ khi câu `vi` đảo thứ tự có chủ ý.
- **Trích dẫn lịch sử** (bản đồ Pháp, khẩu hiệu Trung Quốc, văn bản Qing…): giữ nguyên tên trong nguồn; phần nội dung chính của site dùng cột `hoang_sa` / `truong_sa` của locale.
- **Logo / URL thương hiệu:** `HOÀNG SA`, `hoangsaisland.com`, `truongsaisland.com` — xem [glossary.md § Thương hiệu](./glossary.md#thương-hiệu--url-không-dịch).

## Sáu key trong JSON

| Key | Khi dùng |
|-----|----------|
| `vietnam` | Tên quốc gia (Việt Nam / Vietnam / 越南 / …) |
| `hoang_sa` | Một quần đảo, nhãn bản đồ, câu đơn |
| `truong_sa` | Một quần đảo, câu đơn |
| `combined` | Tiêu đề, meta, footer — cặp tên nối ` - ` |
| `nine_dash_line` | Đường lưỡi bò / Nine-Dash Line — **tên người dùng địa phương thực tế** (không dịch máy tự do) |
| `china` | Trung Quốc / China — **tên địa phương** (vd. zh-cn 中国, jv Tiongkok) |

Placeholder Laravel `:hoangsa`, `:truongsa` — **không** đổi tên biến; giá trị hiển thị lấy từ JSON theo locale.

## Tra cứu nhanh (nhóm locale)

<details>
<summary>Châu Á · Thái Bình Dương</summary>

| Locale | hoang_sa | truong_sa |
|--------|----------|-----------|
| vi | Hoàng Sa | Trường Sa |
| zh-cn | 西沙群岛 | 南沙群岛 |
| zh-tw | 西沙群島 | 南沙群島 |
| ja | 西沙諸島 | 南沙諸島 |
| ko | 파라셀 제도 | 스프래틀리 제도 |
| th | หมู่เกาะพาราเซล | หมู่เกาะสแปรตลี |
| id / ms | Kepulauan Paracel | Kepulauan Spratly |
| jv | Kapuloan Paracel | Kapuloan Spratly |
| fil | Kapuluang Paracel | Kapuluang Spratly |
| mn | Параселийн арлууд | Спратлийн арлууд |

</details>

<details>
<summary>Châu Âu · Mỹ</summary>

| Locale | hoang_sa | truong_sa |
|--------|----------|-----------|
| en | Paracel Islands | Spratly Islands |
| fr | Îles Paracels | Îles Spratleys |
| de | Paracel-Inseln | Spratly-Inseln |
| es | Islas Paracel | Islas Spratly |
| ru | Парасельские острова | Острова Спратли |
| uk | Парасельські острови | Острови Спратлі |
| it | Isole Paracel | Isole Spratly |
| pl | Wyspy Paracelskie | Wyspy Spratly |
| nl | Paraceleilanden | Spratly-eilanden |
| pt | Ilhas Paracel | Ilhas Spratly |
| … | *đủ 58 locale trong* `geo-names.json` | |

</details>

<details>
<summary>Ấn Độ · Trung Đông · RTL</summary>

| Locale | hoang_sa | truong_sa |
|--------|----------|-----------|
| hi | पैरासेल द्वीपसमूह | स्प्रैटली द्वीपसमूह |
| bn | প্যারাসেল দ্বীপপুঞ্জ | স্প্র্যাটলি দ্বীপপুঞ্জ |
| ta / te / mr / gu / ml | *(xem JSON)* | |
| ar | جزر باراسيل | جزر سبراتلي |
| fa | جزایر پاراسل | جزایر اسپراتلی |
| ur | پیراسل جزائر | اسپراٹلی جزائر |
| he | איי פארסל | איי ספרטלי |

</details>

## Lệnh chạy (copy một lượt — WSL)

```bash
cd /var/www/html/hoangsa.dev
```

### A. Chỉ cập nhật tên đã có trong file dịch (nhanh, không MT)

```bash
php scripts/apply-geo-names-lang-ui.php
php scripts/sync-landing-lang-ui.php
php scripts/sync-footer-lang-ui.php
php scripts/patch-meta-from-footer.php
php scripts/patch-hero-title-country.php
php scripts/rebuild-timeline-locales.php
php scripts/patch-beauty-gallery-locales.php
```

Một vài locale:

```bash
php scripts/apply-geo-names-lang-ui.php en fr zh-cn ja
php scripts/sync-landing-lang-ui.php en fr zh-cn ja
php scripts/sync-footer-lang-ui.php en fr
php scripts/patch-hero-title-country.php en fr
```

### B. Dịch lại từ master vi (49 locale)

Admin: `/he-thong/ngon-ngu/{locale}` (AI / Google / Copy theo section) — [LANG-UI-WORKFLOW.md](./LANG-UI-WORKFLOW.md) mục 3.

Không còn `php scripts/translate.php` (CLI MT đã gỡ).

---

## Cập nhật map (chi tiết)

1. Sửa `geo-names.json` (một locale hoặc toàn bộ) — hoặc chỉnh `scripts/publish-geo-names-json.php` rồi chạy để ghi **cả hai** dự án.
2. Chạy khối **A** ở trên trên **hoangsa.dev** (hoặc chỉ các script liên quan).
3. Hero tên quốc gia: key `hero_title_country` (một chuỗi) — `php scripts/patch-hero-title-country.php` lấy `vietnam` từ JSON; **không** dùng `hero_title_vn` / `hero_title_nam` (dễ MT sai).
4. **Tiêu đề ảnh beauty** — [LANG-UI-WORKFLOW.md § 6.1](./LANG-UI-WORKFLOW.md#61-beauty-gallery-tiêu-đề-ảnh): `sync-landing-vi-from-config.php` → `patch-beauty-gallery-locales.php` (không MT toàn bộ `beauty.php`).
