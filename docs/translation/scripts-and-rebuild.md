# Đã chuyển nội dung

→ **[LANG-UI-WORKFLOW.md](./LANG-UI-WORKFLOW.md)**

**Dịch nội dung:** `/he-thong/ngon-ngu/{locale}` · `/he-thong/ngon-ngu-chung-suc/{locale}` (AI / Google trong admin).

**Publish (CLI, không Google MT hàng loạt):**

```bash
php scripts/rebuild-landing-locales.php
php scripts/rebuild-contribute-locales.php
```

**Beauty gallery (tiêu đề ảnh):** mục [6.1](./LANG-UI-WORKFLOW.md#61-beauty-gallery-tiêu-đề-ảnh) — `sync-landing-vi-from-config.php` → `patch-beauty-gallery-locales.php` (không MT toàn bộ `beauty.php`).
