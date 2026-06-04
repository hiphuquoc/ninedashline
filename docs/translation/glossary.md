# Thuật ngữ cố định (glossary)

Dùng **nhất quán** trên toàn site. Không dịch tự do các mục dưới trừ khi [guide.md](./guide.md) cho phép.

## Địa danh Hoàng Sa · Trường Sa

**→ Bảng đầy đủ 50 locale (bản 2026-05):** [`geo-names.json`](./geo-names.json) · hướng dẫn: [`geo-names.md`](./geo-names.md) · **dùng chung** `hoangsa.dev` + `ninedashline.dev`.

| Key JSON | Ý nghĩa |
|----------|---------|
| `vietnam` | Tên quốc gia theo locale |
| `hoang_sa` | Tên quần đảo Hoàng Sa (Paracel) theo locale |
| `truong_sa` | Tên quần đảo Trường Sa (Spratly) theo locale |
| `combined` | Cặp hai tên, nối ` - ` |
| `nine_dash_line` | Đường lưỡi bò / Nine-Dash Line — tên địa phương |
| `china` | Trung Quốc / China — tên địa phương |

Tóm tắt:

- **`vi`:** Hoàng Sa · Trường Sa · Đường lưỡi bò · Trung Quốc
- **Locale khác:** tra JSON (vd. en *Paracel Islands*; zh-cn *西沙群岛* / *断续线* / *中国*).
- Trích dẫn tài liệu đối phương: giữ tên gốc trong ngữ cảnh trích dẫn; phần nội dung site dùng `geo-names.json`.

## Thuật ngữ khác (đa ngôn ngữ)

| Tiếng Việt (vi) | English (en) | zh-cn | zh-tw | ja | ko | Ghi chú |
|-----------------|--------------|-------|-------|-----|-----|---------|
| Biển Đông | East Sea / South China Sea* | 东海 | 東海 | 東シナ海 / 南シナ海* | 동해 / 남중국해* | *en: ưu tiên "East Sea" trên site VN; ghi chú SCS khi cần quốc tế |
| Việt Nam | Vietnam | 越南 | 越南 | ベトナム | 베트남 | |
| Tổ quốc | Fatherland / nation | 祖国 | 祖國 | 祖国 | 조국 | |
| chủ quyền | sovereignty | 主权 | 主權 | 主権 | 주권 | |
| đường lưỡi bò | nine-dash line | 断续线 / 九段线 | 十一段線 / 九段線 | 九段線 | 남중국해 9단선 | **Ưu tiên** `nine_dash_line` trong geo-names.json |
| Đội Hoàng Sa | Hoang Sa flotilla | 黄沙守海队 | 黃沙守海隊 | ホアンサ守海隊 | 황사 수비대 | Tên đội — có thể giữ “Hoang Sa” trong ngữ cảnh lịch sử |
| châu bản | royal archives (Nguyễn) | 朱板文书 | 朱板文書 | 王朝公文書 | 왕조 문서 | |
| UNCLOS | UNCLOS | 《联合国海洋法公约》 | 《聯合國海洋法公約》 | UN海洋法条約 | UNCLOS | Giữ tên hiệp ước |
| San Francisco (1951) | San Francisco Conference | 旧金山和会 | 舊金山和會 | サンフランシスコ講和会議 | 샌프란시스코 회의 | |
| Hải Nam | Hainan | 海南 | 海南 | 海南 | 하이난 | |

Locale chưa có cột trên: dịch tương đương theo chuẩn ngôn ngữ đích, đối chiếu `vi` + `en`.

## Thương hiệu / URL (không dịch)

- `hoangsaisland.com`, `truongsaisland.com`
- `HOÀNG SA` / `HOANG SA` trong logo (`nav_logo`, `loader_title`, `footer_logo`) — có thể giữ Latin hoặc phiên âm theo locale; **không** đổi thành tên Trung Quốc.

## HTML & placeholder

- Giữ nguyên thẻ: `<em>`, `<strong>`, `<br>`, `<span class="accent">`, `<span class="red-accent">`.
- Placeholder Laravel: `:year`, `:hoangsa`, `:truongsa`, `:count` — **không** dịch tên biến.
- Mail body (`mail_*_body`): giữ xuống dòng `\n\n` như bản `vi`.

## Số & đơn vị

- Năm: giữ số Ả Rập (1974, 1686).
- Hải lý: en `nm`; zh `海里`; ja `海里`; ko `해리`; th `ไมล์ทะเล` — thống nhất trong từng locale.
- Tọa độ: giữ `16°30′N · 112°00′E` (đổi B/Đ → N/E theo locale).
