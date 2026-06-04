# Đa ngôn ngữ landing — Hoàng Sa · Trường Sa

## Bắt đầu tại đây

| Ưu tiên | File | Khi nào mở |
|---------|------|------------|
| **1** | **[LANG-UI-WORKFLOW.md](./LANG-UI-WORKFLOW.md)** | Sửa chữ, dịch admin, publish/sync script, rebuild |
| 2 | [guide.md](./guide.md) | Quy chuẩn dịch (tone, HTML) |
| 3 | [geo-names.json](./geo-names.json) | Tên Hoàng Sa / Trường Sa theo locale |
| 4 | [batch-locales.md](./batch-locales.md) | Danh sách 49 locale landing |

## Tài liệu bổ sung

| File | Mục đích |
|------|----------|
| [geo-names.md](./geo-names.md) | Hướng dẫn dùng map địa danh |
| [glossary.md](./glossary.md) | Thuật ngữ (Biển Đông, chủ quyền, …) |
| [ai-prompt.md](./ai-prompt.md) | Prompt AI / biên dịch viên |
| [status.md](./status.md) | Tiến độ từng locale |
| [scripts-and-rebuild.md](./scripts-and-rebuild.md) | Redirect → LANG-UI-WORKFLOW |

## Cấu trúc code (tóm tắt)

- **Master:** `config/lang_ui/vi/*.php`
- **Hiển thị web:** `config/lang_ui/{locale}/*.php` — `t('key')`
- **10 file / locale:** `nav`, `meta`, `hero`, `mapview`, `timeline`, `evidence`, `beauty`, `history`, `action`, `footer`
- **Chung sức:** `config/lang_ui/{locale}/chung_suc.php` (build từ `scripts/lang-data/contribute/`)

Chi tiết file nào sửa khi đổi caption / timeline / landing: **[LANG-UI-WORKFLOW.md](./LANG-UI-WORKFLOW.md)** mục 1.

## Dịch & lệnh nhanh

**Dịch nội dung:** `/he-thong/ngon-ngu/{locale}` (landing) · `/he-thong/ngon-ngu-chung-suc/{locale}` (AI / Google / Copy+Nhập).

Chi tiết: **[LANG-UI-WORKFLOW.md](./LANG-UI-WORKFLOW.md)**

```bash
cd /var/www/html/hoangsa.dev

# Publish sau khi sửa lang-data / timeline:
php scripts/rebuild-landing-locales.php
php scripts/rebuild-contribute-locales.php

# Chỉ đổi tên đảo (không dịch lại): geo-names.md
php scripts/apply-geo-names-lang-ui.php
```
