# Deploy — ninedashline.dev / ninedashline.net

## Lỗi `Vite manifest not found`

Nguyên nhân: trên server **không có** `public/build/manifest.json` (thường vì `.gitignore` cũ bỏ qua `public/build`).

**Cách xử lý (một lần trên máy dev):**

```bash
cd /var/www/html/ninedashline.dev
npm ci
npm run build
git add public/build
git commit -m "Add Vite production build for deploy without npm on server"
git push
```

Trên server:

```bash
cd /www/wwwroot/ninedashline.net   # đổi đúng path
git pull
ls -la public/build/manifest.json   # phải tồn tại
php artisan storage:link
php artisan optimize:clear
```

## Quy trình deploy thường ngày

1. **Máy dev:** sửa code → `npm run build` nếu đổi CSS/JS/SCSS → commit `public/build` + code.
2. **Server:** `git pull` — **không** chạy `npm run build`.

## Cache Laravel

```bash
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
```

Tránh `php artisan optimize` / `config:cache` trên site đa ngôn ngữ `lang_ui` (xem hoangsa `docs/DEPLOY.md`).

## Site luôn hiển thị tiếng Việt dù chọn ngôn ngữ khác

**Triệu chứng:** `/ja`, `/en`, … vẫn ra chữ Việt (hoặc phần lớn là Việt).

**Nguyên nhân thường gặp:**

1. `config:cache` / `optimize` khiến `config('lang_ui')` chỉ còn `vi` — chạy `php artisan optimize:clear`.
2. Master `vi` có thêm file `ancient_maps.php` mà locale khác chưa có — logic cũ loại hết locale hoặc `hasLocale` sai → fallback `vi` trong `t()`.
3. Key `ancient_map_*` chỉ có ở `vi` — timeline bản đồ cổ fallback Việt cho đến khi dịch file `ancient_maps.php`.

**Khắc phục:**

```bash
cd /var/www/html/ninedashline.dev
php artisan optimize:clear
php artisan config:clear
php artisan view:clear
php artisan lang-ui:status
```

Kỳ vọng: `contentLocales` ≈ 50, `hero_title_line1` của `en`/`ja` khác tiếng Việt.

## Quyền thư mục (aaPanel, user `www`)

```bash
chown -R www:www storage/app/public
find storage/app/public -type d -exec chmod 755 {} \;
find storage/app/public -type f -exec chmod 644 {} \;

chown -R www:www config/lang_ui storage/framework storage/logs bootstrap/cache
chmod -R ug+rwX config/lang_ui storage/framework storage/logs bootstrap/cache
```

`public/build` chỉ cần đọc (644 file / 755 thư mục) — thường đủ sau `git pull` với quyền mặc định.
