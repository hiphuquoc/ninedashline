# ninedashline.dev — Design System

> **Dự án:** The Ocean of Truth — Giải thích "Đường chín đoạn" (Nine-Dash Line) dưới góc độ pháp lý, lịch sử và sự thật khách quan.
> **Kế thừa phong cách từ:** `hoangsa.dev` (cùng hệ sinh thái chủ quyền biển đảo Việt Nam).
> **Tinh thần:** Điềm tĩnh · quyền uy (authoritative) · minh bạch · giải thích — **không kích động, không thù ghét, không kêu gọi cảm xúc dân tộc**. Thuyết phục bằng logic, luật pháp và bằng chứng.

---

## 1. Nguyên tắc thiết kế (Design Principles)

1. **Sự thật trước, cảm xúc sau (Evidence-first).** Mỗi luận điểm đi kèm mốc thời gian, trích dẫn, hoặc nguồn. Giao diện như một hồ sơ pháp lý / phòng triển lãm số.
2. **Giải thích, không phán xét.** Tông màu trầm tĩnh; đỏ (`--red`) chỉ dùng rất hạn chế để đánh dấu *điểm xung đột / sai lệch*, không dùng để "đả kích".
3. **Phân cấp rõ ràng (Clear hierarchy).** Mọi section theo cùng một khung: `label → title → lead → nội dung`.
4. **Cinematic nhưng nhẹ.** Hiệu ứng phục vụ sự tin cậy (trust), không gây xao nhãng. Ưu tiên Lighthouse ≥ 90, tôn trọng `prefers-reduced-motion`.
5. **Nhất quán với hoangsa.dev.** Dùng chung bảng màu, font, cấu trúc class để hai site là một thể thống nhất.

---

## 2. Bảng màu (Color Tokens)

Bảng màu cờ Việt Nam (đỏ–vàng) trên nền hải dương sâu (navy/dark).

| Token | HEX | Vai trò |
|---|---|---|
| `--red` | `#DA251D` | Đỏ cờ — nhấn xung đột / sai lệch / điểm nóng. **Dùng tiết chế.** |
| `--gold` | `#FFCC00` | Vàng sao — accent chính, eyebrow, CTA, số liệu nổi bật, đường kẻ. |
| `--navy` | `#001F3F` | Xanh hải quân — nền phụ, gradient. |
| `--navy2` | `#002B5B` | Navy sáng hơn cho lớp gradient. |
| `--cream` | `#F5EDD6` | Màu chữ thân (trên nền tối). |
| `--white` | `#FFFFFF` | Tiêu đề lớn, độ tương phản cao. |
| `--dark` | `#050A14` | Nền chính của body. |
| `--surface-deep` | `#000511` | Nền sâu nhất (hero, đáy section). |
| `--surface-base` | `#050A14` | Nền section tiêu chuẩn. |
| `--surface-mid` | `#030B1A` | Nền section xen kẽ. |
| `--surface-elevated` | `#050D1A` | Card / panel nổi. |
| `--overlay` | `rgba(0,15,35,0.82)` | Lớp phủ trên ảnh nền. |

### Quy ước dùng màu (opacity ramp cho chữ trên nền tối)
- Tiêu đề: `var(--white)`
- Lead/quote: `rgba(245,237,214,0.78)` → `0.85`
- Body: `rgba(245,237,214,0.9)`
- Caption/meta: `rgba(245,237,214,0.5)`
- Viền mảnh: `rgba(255,255,255,0.1)`

### Accent có ngữ nghĩa (semantic chips)
- PCA / luật: vàng `rgba(255,204,0,*)`
- UNGA / phản đối: cam-đỏ `rgba(241,121,94,*)`
- FONOP / quốc tế: xanh `rgba(108,174,255,*)`
- ASEAN / hợp tác: xanh ngọc `rgba(116,210,179,*)`

---

## 3. Typography

### Font families
| Token | Font | Dùng cho |
|---|---|---|
| `--font-sans` | **Lexend**, sans-serif | Body, nav, label, caption, UI. |
| `--font-serif-title` | **Playfair Display**, serif | Tiêu đề lớn (h1/h2), hero title. |
| `--font-serif-text` | **Cormorant Garamond**, serif | Lead, trích dẫn (italic), văn bản trang trọng. |
| `--font-display` | **Barlow Condensed**, sans-serif | Logo/brand, năm (year), số đếm, nhãn condensed. |

Google Fonts:
```
Lexend:wght@300;400;500;600;700
Playfair+Display:wght@400;500;600;700;800;900
Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500
Barlow+Condensed:wght@400;500;600;700;900
```

### Thang cỡ chữ (responsive, dùng `clamp`)
| Vai trò | Class | Cỡ |
|---|---|---|
| Hero title | `.hero-title` | `clamp(44px, 8vw, 92px)` — Playfair 700 |
| Section title (H2) | `.section-title` | `clamp(36px, 5vw, 64px)` — Playfair 700, line-height 1.1 |
| Subtitle (H3) | `.section-subtitle` | `clamp(22px, 2.4vw, 30px)` |
| Lead | `.prose-lead` | `clamp(20px, 2.1vw, 25px)` — Cormorant 500, line-height 1.7 |
| Body | `.prose-body` | `clamp(17px, 1.28vw, 20px)` — Lexend 300, line-height 1.7 |
| Quote | `.prose-quote` | `clamp(20px, 2.1vw, 25px)` — Cormorant italic, viền đỏ trái |
| Caption/eyebrow | `.section-label` / `.prose-caption` | `11–15px`, uppercase, letter-spacing 2px |
| Year/display | `.display` | Barlow Condensed, `clamp(26px, 2.4vw, 38px)` |

Quy ước nhấn: `.section-title .accent { color: var(--gold) }`, `.section-title .red-accent { color: var(--red) }`. Trong `.prose-body em` → đỏ, không nghiêng, đậm.

---

## 4. Spacing & Layout

> **Nguồn đầy đủ:** [`docs/typography.md` §8–10](./typography.md#8-spacing-tokens-margin--padding--gap) — bảng token, breakpoint (1199 / 1023 / 990 / 768 / 567), margin/padding theo từng section, quy tắc lưới 1 cột.

| Token | Desktop | Ý nghĩa |
|---|---|---|
| `--section-max` | `1140px` | Bề ngang `.section-inner`. |
| `--section-x` | `60px` → `22px` (XS) | Padding ngang section / footer. |
| `--section-pad-y` | `130px` → `68px` (XS) | Padding dọc mỗi section. |
| `--intra-gap` | `52px` → `32px` (XS) | Khối lớn sau lead (maps, thesis, stat-band…). |
| `--intra-gap-md` | `44px` → `26px` (XS) | Khối phụ (spine, subtitle PCA…). |
| `--thesis-gap` | `clamp(40px,5vw,64px)` → `28px` (XS) | Ledger / collision / khối “luận điểm thứ hai”. |
| `--gap-content` | `16px` → `10px` (XS) | Label → title → lead. |
| `--gap-card` / `--gap-grid` | `14px` / `16–22px` | Stack card & lưới cột. |
| `--pad-card` / `--pad-card-sm` | `22–28px` / `18–24px` | Padding trong panel. |
| `--nav-chip-radius` | `8px` | Bo góc nút nav/FAB. |

**Khung section chuẩn:**
```
<section id="...">
  <div class="light-leak leak-gold" ...></div>   {{-- trang trí --}}
  <div class="section-inner">                      {{-- max 1100px, padding 100/60 --}}
    <div class="section-label reveal">NHÃN</div>
    <h2 class="section-title reveal reveal-delay-1">Tiêu đề <span class="accent">nhấn</span></h2>
    <p class="prose-lead reveal reveal-delay-2">Dẫn nhập…</p>
    … nội dung …
  </div>
</section>
```

Bo góc: card lớn `2px` (sắc, mang tính "hồ sơ"), chip/pill `99px`. Border mảnh `1px rgba(255,255,255,0.08–0.12)`.

---

## 5. Components

### 5.1 Section label (eyebrow)
Chữ vàng uppercase + một gạch ngang `30px` phía trước (`::before`). Luôn mở đầu mỗi section.

### 5.2 Buttons / CTA
- **Primary** (`.hero-cta`/`.btn-gold`): nền `--gold`, chữ `--dark`, uppercase, letter-spacing 2px, padding `16px 40px`. Hover: lớp `--red` quét từ trái (`scaleX`), chữ chuyển trắng.
- **Secondary** (`.btn-ghost`): viền `1px rgba(255,204,0,.4)`, nền trong suốt, hover nền vàng mờ.

### 5.3 Cards / Panels
- Nền `--surface-elevated` hoặc gradient navy; viền mảnh; padding `clamp(20px,2vw,28px)`.
- "Ledger / pillar / voice": tiêu đề (Playfair) + kicker (caption) + body + foot (caption). Số La Mã / số thứ tự dạng badge vàng.

### 5.4 Timeline
- Trục dọc với badge số (`01, 02…`), dot, card xen kẽ trái/phải (`--alt`), mốc `--pivotal` (vàng) và `--ongoing`.
- "Span" tóm tắt mốc lớn (year + label) dạng hàng ngang ở đầu.

### 5.5 Comparison ledger (VS)
- 2 panel (VN ✦ / bên kia —) + trục giữa có "VS" và biểu tượng cân ⚖. Dùng để đối chiếu pháp lý — trình bày cân bằng, dữ kiện.

### 5.6 Chips (ledger)
Pill uppercase 11–12px, có biến thể ngữ nghĩa (pca/unga/fonops/asean — xem mục màu).

### 5.7 Quote / Seal
`.prose-quote`: viền trái đỏ `3px`, nền `rgba(218,37,29,0.05)`, Cormorant italic; `<strong>` chuyển vàng.

### 5.8 Footer (`.site-foot`)
- **Tông chủ đạo:** xanh biển sâu (`#061428` → `#000511`), accent `rgba(108,174,255,*)` + vàng cho CTA/nhấn.
- **Lớp trang trí:** `.foot-waves` (sóng SVG đầu footer), `.foot-void` (đường chín đoạn đứt nét đỏ + dấu X xanh — phủ định, opacity thấp).
- **Khối:** `.foot-crest` (quote kết), `.foot-navstrip` + `.foot-btn`, `.foot-deck` / `.foot-panel`, `.foot-trench` (copyright + chip UNCLOS/PCA).
- **Liên kết hệ sinh thái:** `paracelislands.net` trong navstrip và trench.

---

## 6. Decor & Atmosphere

| Lớp | Mô tả | z-index gợi ý |
|---|---|---|
| `.grain` | Film grain SVG (`--grain`), `background-size:128px`, opacity ~0.4 (trong hero) / ~0.05 (toàn trang). | nền trong section |
| `.light-leak` | Vòng tròn blur 80px, đường kính 300px. Biến thể `leak-red/leak-gold/leak-blue` (opacity 0.06–0.08). | trang trí, dưới nội dung |
| `.stars` | Lớp sao mờ ở hero. | nền |
| `.wave` (wave1/2/3) | 3 lớp sóng SVG `preserveAspectRatio:none`, animate trôi ngang khác pha. | nền hero |
| `island-silhouette` | Bóng đảo + ★ vàng mờ. | nền hero |

Tất cả `pointer-events:none`.

---

## 7. Motion

- **Reveal:** `.reveal { opacity:0; translateY(40px) }` → `.reveal.visible { opacity:1; none }`, transition `0.8s ease`. Trigger bằng `IntersectionObserver`. Delay: `.reveal-delay-1..4` (0.1/0.2/0.35/0.5s).
- **Hero intro:** `fadeUp`/`fadeIn` theo thứ tự label → title → divider → sub → cta (delay 0.3→2s).
- **Nav:** thêm `.scrolled` khi `scrollY>40` (nền đậm + blur + thu gọn padding).
- **Custom cursor:** dot vàng (`mix-blend-mode:difference`) + ring; `.expand` khi hover link/nút (to ra, đỏ mờ). Ẩn trên `pointer:coarse`.
- **Easing chủ đạo:** `cubic-bezier(0.32,0.72,0,1)` cho chuyển động UI; `ease` cho fade.
- **Reduced motion:** khi `prefers-reduced-motion: reduce` → tắt animation, hiện nội dung tĩnh, tắt cursor tùy biến.

---

## 8. Accessibility

- Tương phản: chữ thân `rgba(245,237,214,.9)` trên `#050A14` đạt ≥ 4.5:1. Không đặt chữ đỏ trên nền tối cho đoạn dài.
- Mọi `<img>` có `alt`; ảnh trang trí `aria-hidden="true"`.
- Vùng tương tác ≥ 40px; focus ring rõ (không chỉ dựa vào cursor tùy biến).
- Cấu trúc heading đúng cấp (1×h1 hero, mỗi section 1×h2).
- Hỗ trợ bàn phím cho mọi phần tử bấm được; `prefers-reduced-motion` được tôn trọng.
- Ngôn ngữ chính: `lang="vi"`.

---

## 9. Nội dung & Tông giọng (Content Voice)

**Mục tiêu trang:** GIẢI THÍCH, không công kích.

| Nên (Do) | Không nên (Don't) |
|---|---|
| "Đường chín đoạn không có tọa độ địa lý cụ thể." | "Trung Quốc tham lam / phi pháp trắng trợn." |
| Trích phán quyết PCA, dẫn nguồn. | Khẳng định cảm tính, không nguồn. |
| "Cộng đồng quốc tế phản đối vì…" (lý do) | Kêu gọi thù ghét / dân tộc cực đoan. |
| Dẫn dắt bằng logic → kết luận Hoàng Sa–Trường Sa của VN là tất yếu. | Mở đầu bằng khẩu hiệu chủ quyền. |
| Dùng thuật ngữ chuẩn: EEZ, UNCLOS, quyền lịch sử, thềm lục địa. | Ngôn ngữ giật gân. |

**Mạch nội dung (storyline):**
1. Hero — *Đường chín đoạn là gì?*
2. Định nghĩa & phạm vi (~75% Biển Đông).
3. Nguồn gốc (1948 → 1953 → 2014; vẽ tay, không tọa độ).
4. Vì sao gây tranh cãi (không tọa độ, bản đồ mâu thuẫn, lỗi tỉ lệ, chồng lấn EEZ).
5. Vì sao quốc tế phản đối (VN/PH/MY/ID; phân tích Bộ Ngoại giao Hoa Kỳ).
6. Vì sao PCA 2016 bác bỏ + xung đột với UNCLOS.
7. Dẫn sang: **Hoàng Sa – Trường Sa là của Việt Nam** (chiếm hữu hòa bình, liên tục, minh bạch từ TK XVII).
8. Footer — thư viện mở, nguồn, kết bằng tinh thần thượng tôn pháp luật.

---

## 10. Tech & Cấu trúc

- **Laravel Blade.** Giai đoạn đầu: dồn toàn bộ vào `resources/views/welcome.blade.php` (1 file) để duyệt nhanh; sau khi chốt sẽ tách thành `landing/partials/*` như hoangsa.dev.
- **CSS:** dùng CSS variables như bảng trên. Khi tách sẽ chuyển sang `resources/css/landing.css` + Vite.
- **JS:** vanilla (IntersectionObserver cho reveal, cursor, nav scrolled, scroll progress). GSAP/Three.js là tùy chọn nâng cao, chỉ thêm nếu không ảnh hưởng tốc độ.
- **Đặt tên class:** kế thừa hoangsa.dev (`section-inner`, `section-label`, `section-title`, `prose-*`, `reveal`, `light-leak`, `grain`) để dễ hợp nhất.

---

*Tài liệu này là nguồn chân lý (source of truth) về thiết kế cho toàn dự án ninedashline.dev. Mọi section/component mới phải tuân theo token, typography và tông giọng ở trên.*
