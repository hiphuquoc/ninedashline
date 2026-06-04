# Typography System — ninedashline.dev

Hệ thống typographic cho `ninedashline.dev`, kế thừa và đồng bộ với dự án **hoangsa.dev** để giữ một ngôn ngữ thiết kế nhất quán giữa hai trang.

Mục tiêu: trình bày **bằng chứng và luật pháp** một cách điềm tĩnh, học thuật, dễ đọc — không kích động cảm xúc. Chữ phải tạo nhịp điệu rõ ràng giữa "tuyên bố" (serif lớn) và "diễn giải" (sans nhẹ).

---

## 1. Font families (4 vai trò)

| Token | Font | Vai trò |
|---|---|---|
| `--font-serif-title` | **Playfair Display** | Tiêu đề lớn, câu tuyên bố (display serif, độ tương phản nét cao) |
| `--font-serif-text` | **Cormorant Garamond** | Lead / trích dẫn (serif văn học, in nghiêng tinh tế) |
| `--font-sans` | **Lexend** | Văn bản nội dung, nhãn, UI (sans dễ đọc) |
| `--font-display` | **Barlow Condensed** | Số liệu, eyebrow, logo, nhãn nén (condensed, uppercase) |

```
--font-sans:        'Lexend', sans-serif;
--font-serif-title: 'Playfair Display', serif;
--font-serif-text:  'Cormorant Garamond', serif;
--font-display:     'Barlow Condensed', sans-serif;
```

Google Fonts (weights đang dùng):
- Playfair Display: 400–900 (+ italic 500)
- Cormorant Garamond: 400/500/600 (+ italic 400/500)
- Lexend: 200–700
- Barlow Condensed: 400/500/600/700/900

---

## 2. Thang chữ (type scale & roles)

Đồng bộ trực tiếp với hoangsa.dev.

### Eyebrow — `.section-label`
- Font: Lexend · **15px** · 600 · `letter-spacing: 2px` · UPPERCASE · màu `--gold`
- Có gạch vàng `30px × 1px` đứng trước (`::before`)
- `.lnum` (số thứ tự section): Barlow Condensed 700, màu mờ `rgba(245,237,214,.35)`

### Tiêu đề section — `.section-title`
- Font: Playfair Display · `clamp(36px, 5vw, 64px)` · 700 · `line-height: 1.1` · màu `--white`
- Accent: `.accent` → vàng, `.red-accent` → đỏ, `em` → italic 500

### Lead — `.prose-lead`
- Font: Cormorant Garamond · **`clamp(22px, 2.2vw, 25px)`** (desktop **25px**) · 500 · `line-height: 1.7` · `rgba(245,237,214,.7)`
- `strong` → vàng 600; `em` → in nghiêng tên gọi/thuật ngữ

### Body — `.prose-body`
- Font: Lexend · **`clamp(18px, 1.15vw, 20px)`** (desktop **20px**) · 300 · `line-height: 1.7` · `rgba(245,237,214,.9)`
- `em` → đỏ, **không nghiêng**, 600 (nhấn thuật ngữ pháp lý)
- **Không** dùng cỡ dưới 18px cho đoạn văn chính — tránh “chữ nhỏ khó đọc” trong box nội dung (hồ sơ, timeline, ledger).

### Quote — `.prose-quote`
- Font: Cormorant Garamond · 25px · italic 500 · `line-height: 1.6`
- Viền trái `3px var(--red)`, nền `rgba(218,37,29,.05)`
- `strong` → vàng 600, không nghiêng

### Caption / nhãn nhỏ — `.prose-caption` · `.voices-eyebrow`
- Font: Lexend · **11–15px** · `letter-spacing: 2px` · UPPERCASE
- Eyebrow trong box (`.voices-eyebrow`): **15px** — đồng bộ `.section-label`
- Caption thuần: **11px** · `rgba(245,237,214,.5)`

### Tiêu đề phụ trong box — `h4` trong ledger / timeline
- Font: Playfair Display · **`clamp(19px, 1.8vw, 22px)`** · 600 · màu trắng

### Số liệu — `.stat-num`
- Font: Barlow Condensed · `clamp(36px, 4.6vw, 58px)` · 700 · màu vàng (`.red` → đỏ)
- Nhãn `.stat-label`: Lexend 12px, `letter-spacing: 1.3px`, UPPERCASE, mờ `.6`

---

## 3. Nguyên tắc nhịp điệu

- **Tương phản vai trò**: tuyên bố = serif lớn (Playfair); diễn giải = sans nhẹ (Lexend 300). Không trộn ngược.
- **Một accent mỗi cụm**: tô vàng/đỏ **một** từ khoá, không tô cả câu.
- **Letter-spacing tăng dần theo độ nhỏ**: chữ càng nhỏ + uppercase → spacing càng rộng (eyebrow 2px, caption 2px, label số liệu 1.3px). Tiêu đề lớn dùng spacing âm nhẹ (`-0.01em`).
- **Line-height**: tiêu đề 1.1; lead/body 1.7; quote 1.6.
- **Độ rộng dòng đọc**: body/lead giới hạn `~62ch` để dễ đọc.
- **Italic** chỉ cho thuật ngữ nước ngoài (Cormorant) hoặc trích dẫn — không dùng để nhấn mạnh số liệu.

---

## 4. Màu chữ (tham chiếu)

```
--white  #FFFFFF      tiêu đề
--cream  #F5EDD6      nền chữ (qua alpha .6–.9)
--gold   #FFCC00      accent chính, eyebrow, số liệu
--red    #DA251D      accent cảnh báo, thuật ngữ pháp lý (em trong body)
```

Quy ước alpha trên nền tối:
- 0.9 → body chính
- 0.8 → lead
- 0.6 → nhãn/caption phụ
- 0.35–0.5 → metadata mờ (lnum, caption)

---

## 5. Reveal (chuyển động chữ)

`.reveal` → mờ + dịch lên 40px, hiện khi cuộn tới. Delay theo cấp: `.reveal-delay-1/2/3/4` = 0.1/0.2/0.35/0.5s. Eyebrow → title → lead → nội dung hiện tuần tự.

Tôn trọng `prefers-reduced-motion`: tắt animation, hiện chữ ngay.

---

## 6. Checklist khi thêm nội dung mới

- [ ] Eyebrow dùng `.section-label` (+ `.lnum` nếu là section đánh số)
- [ ] Tiêu đề dùng `.section-title`, accent đúng 1 từ khoá
- [ ] Đoạn mở đầu = `.prose-lead`; đoạn thường = `.prose-body`
- [ ] Trích dẫn = `.prose-quote`; chú thích ảnh/biểu đồ = `.prose-caption`
- [ ] Số liệu = `.stat-num` + `.stat-label`
- [ ] Không tạo size/letter-spacing tuỳ ý — chọn vai trò có sẵn ở mục 2

---

## 7. Section `#sovereignty` (Kết luận tất yếu)

Typography scoped trong `welcome.blade.php` — `#sovereignty { … }` + class box.

| Vùng | Class / element | Vai trò typographic |
|---|---|---|
| Mở section | `.section-label`, `.section-title`, `.prose-lead` | Chuẩn mục 2 |
| Luận điểm | `.sov-thesis__label.prose-caption`, `.sov-thesis__locks li`, `.sov-thesis__body.prose-body` | Caption 11–14px; body 18–20px Lexend |
| Biên niên | `.sov-spine__head .prose-caption`, `.section-subtitle`, `.sov-chron__yr` (Barlow), `.sov-chron__title` (Playfair 19–22px), `.prose-body` | Năm = display; tiêu đề mốc = h4 box |
| Cân bằng | `.sov-balance__label.prose-caption`, `.sov-balance__tag.prose-caption`, `h4`, `.prose-body` | Hai cột; tag vàng / xanh |
| Lập trường | `.sov-seal__quote` (Cormorant 20–25px italic), `.sov-seal__cite.prose-caption` | Quote trang trọng, không dùng `.prose-quote` viền đỏ (căn giữa seal) |
| Liên kết dự án | `.sov-next__eyebrow.prose-caption`, `.section-subtitle`, `.prose-body`, `.sov-next__cta` | CTA Lexend 15–16px 600 |

**Quy tắc:** Mọi đoạn đọc trong box dùng `.prose-body` (≥18px). Không dùng `.sov-chron__text` hay size riêng dưới 18px.

---

## 8. Spacing tokens (margin / padding / gap)

Áp dụng qua CSS variables trên `:root` trong `welcome.blade.php`. **Không** hard-code padding section lẻ tẻ — ưu tiên token.

### 8.1 Token toàn cục

| Token | Desktop (≥1200px) | Ý nghĩa |
|---|---|---|
| `--section-max` | `1140px` | Bề ngang `.section-inner` |
| `--section-x` | `60px` | Padding ngang section / footer inner |
| `--section-pad-y` | `130px` | Padding dọc mỗi section |
| `--intra-gap` | `52px` | Khoảng cách **khối lớn** trong section (sau lead → nội dung chính) |
| `--intra-gap-md` | `44px` | Khối phụ (thesis, spine head → chronicle) |
| `--gap-content` | `16px` | Label → title → lead (stack tiêu đề) |
| `--gap-card` | `14px` | Giữa card trong list / stack (`.op-voices`, ledger rows) |
| `--gap-grid` | `clamp(16px, 2.2vw, 22px)` | Cột lưới (maps, pca, foot-deck) |
| `--pad-card` | `clamp(22px, 2.6vw, 28px)` | Padding trong card / panel |
| `--pad-card-sm` | `clamp(18px, 2.2vw, 24px)` | Card nhỏ, chron row |
| `--stack-sm` | `12px` | Stack chặt (chip, tag) |
| `--stack-md` | `14px` | Stack vừa (voice grid gap) |

**Công thức nhịp điệu dọc trong section:**
```
.section-label  → margin-bottom: var(--gap-content)
.section-title  → margin-bottom: var(--gap-content)
.prose-lead      → (không margin-bottom bắt buộc; khối tiếp theo dùng margin-top)
Khối nội dung    → margin-top: var(--intra-gap)  (lần đầu sau lead)
Khối phụ          → margin-top: var(--intra-gap-md)
```

### 8.2 Breakpoint — giá trị token

| Token | ≥1200 | ≤1199 | ≤1023 | ≤990 | ≤768 | ≤567 |
|---|---:|---:|---:|---:|---:|---:|
| `--section-max` | 1140px | 1100px | 100% | 100% | 100% | 100% |
| `--section-x` | 60px | 52px | 44px | 36px | 28px | 22px |
| `--section-pad-y` | 130px | 118px | 104px | 96px | 80px | 68px |
| `--intra-gap` | 52px | 48px | 44px | 40px | 36px | 32px |
| `--intra-gap-md` | 44px | 40px | 38px | 34px | 30px | 26px |
| `--gap-content` | 16px | 15px | 14px | 14px | 12px | 10px |
| `--gap-card` | 14px | 14px | 13px | 12px | 12px | 10px |
| `--gap-grid` | 16–22px | 16–20px | 14–18px | 14–16px | 12–14px | 12px |
| `--pad-card` | 22–28px | 20–26px | 20–24px | 18–22px | 18–20px | 16–18px |

**Nav** (không dùng token section): chuyển layout 2 hàng tại **≤1023px**; thu gọn label **≤768px**.

**Footer** `.site-foot__inner`: `padding-top` = `clamp(72px, 9vw, 100px)` desktop; giảm ~12% mỗi bậc ≤990 và ≤567.

### 8.3 Margin / padding theo section

Số liệu desktop; mobile kế thừa `--intra-gap` / `--section-x` đã scale.

| Section | Sau `.prose-lead` | Khối chính | Gap nội bộ / grid | Ghi chú |
|---|---|---|---|---|
| **Hero** `#hero` | — | `padding` ngang `32px` → `var(--section-x)` ≤768 | actions `gap: 14px` | `min-height: 720px`; fact pill `top: 98px` |
| **01** `#what` | `--intra-gap` → `.def-feature` | cột `gap: clamp(28–56px)` → 1 cột ≤990 | stat-band `margin-top: intra-gap` | sticky map tắt ≤990 |
| **02** `#origin` | `--intra-gap` → banner | ledger `gap: 0`; o-answer stack ≤768 | orig-verdict `margin-top: 44–68px` | banner 1 cột ≤567 |
| **03** `#dispute` | `--intra-gap` → `.dispute-thesis` | pillars/ledger `gap: var(--gap-card)`; ledger `margin-top: var(--thesis-gap)` | collision `pad: var(--pad-card)` | collision `margin-top: var(--thesis-gap)` |
| **04** `#opposition` | `--intra-gap` → `.op-voices` | voices `gap: var(--gap-card)` | voice 1 cột ≤768 | |
| **05** `#witnesses` | `--intra-gap` → `.wit-maps` | maps `gap: var(--gap-grid)` | 1 cột ≤990 | |
| **06** `#verdict` | `--intra-gap` → `.pca-no3` | no3/extra `gap: var(--gap-grid)` / `--gap-card` | 1 cột no3 ≤990 | |
| **07** `#sovereignty` | `--intra-gap` → `.sov-thesis` | spine `var(--intra-gap-md)`; balance `gap: var(--gap-grid)`; next `margin-top: var(--intra-gap-md)` | chron rail vars ≤567 | |
| **Footer** | crest → nav `40–52px` | deck `gap: grid`; panel `pad-card` | deck 1 cột ≤990 | |

### 8.4 Quy tắc lưới (khi nào 1 cột)

| Breakpoint | Lưới / layout chính |
|---|---|
| **≤1199px** | Thu scale tiêu đề hero/section; decor `#sovereignty::before` nhỏ hơn |
| **≤1023px** | Nav 2 hàng; `.origin-record` / `.orig-banner` thu padding |
| **≤990px** | `.def-feature`, `.wit-maps`, `.pca-no3`, `.foot-deck`, `.dvq-grid`, `.orig-banner`, `.o-answer` → 1 cột |
| **≤768px** | `.stat-band` 2×2; `.op-voice`, `.d-pillar-grid`, `.d-coll__rule`, `.d-coll-compare`, `.pca-extra`, `.pca-unclos`; `.sov-balance` dọc + medallion giữa; `.sov-next` CTA full width |
| **≤567px** | `.stat-band` 1 cột; hero CTA stack; `.sov-chron` grid 2 vùng (rail + year/main); footer nút full width; `.d-coll-shift` stack |

CSS triển khai: khối `/* RESPONSIVE — phải đặt CUỐI file */` trong `welcome.blade.php` — **bắt buộc sau mọi rule component**, nếu không các `grid-template-columns` desktop sẽ ghi đè `@media`.

---

## 9. Breakpoint chuẩn (responsive)

Dùng **một bộ** `max-width` trên toàn landing — không thêm 760px / 920px lẻ.

| Tên | Query | Thiết bị gợi ý |
|---|---|---|
| Desktop | mặc định | ≥1200px |
| XL↓ | `(max-width: 1199px)` | Laptop nhỏ / tablet ngang lớn |
| LG↓ | `(max-width: 1023px)` | Tablet ngang, nav 2 hàng |
| MD↓ | `(max-width: 990px)` | Tablet dọc / layout 1 cột “rộng” |
| SM↓ | `(max-width: 768px)` | Mobile lớn |
| XS↓ | `(max-width: 567px)` | Mobile nhỏ |

**Thứ tự file CSS:** token `:root` theo breakpoint (1199 → 567) → layout grids (990 / 768 / 567) → override component (nếu cần).

---

## 10. Checklist spacing khi thêm section

- [ ] `.section-inner` chỉ padding qua `--section-pad-y` / `--section-x`
- [ ] Khối đầu sau lead: `margin-top: var(--intra-gap)`
- [ ] Card/list: `gap: var(--gap-card)` hoặc `--gap-grid`
- [ ] Padding card: `var(--pad-card)` — không dưới 16px mobile
- [ ] Media query chỉ dùng 1199 / 1023 / 990 / 768 / 567
- [ ] Cập nhật bảng 8.3 nếu thêm section mới
