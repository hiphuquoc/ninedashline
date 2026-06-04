# Admin — ninedashline.dev

Hệ thống giống **hoangsa.dev**: bản dịch file `config/lang_ui/{locale}/*.php`, master locale **vi**, admin tại `/he-thong`.

## Cài đặt (WSL)

```bash
cd /var/www/html/ninedashline.dev
bash scripts/install-admin-from-hoangsa.sh   # SCSS → resources/sources/*.scss
npm install && npm run build                 # bắt buộc trước khi push — server không build
git add public/build                         # commit manifest + assets (xem docs/DEPLOY.md)
composer dump-autoload
php artisan migrate --force
php artisan ninedashline:admin-user --email=admin@example.com --password='Mat-khau-manh'
php artisan view:clear
```

## URL

| URL | Mô tả |
|-----|--------|
| `/he-thong` | Đăng nhập |
| `/he-thong/ngon-ngu/vi` | Sửa nội dung master (tiếng Việt) |
| `/he-thong/ngon-ngu/en` | Bản dịch tiếng Anh (+ AI / Google / import JSON) |
| `/logout` | Đăng xuất |
| `/en` | Xem landing tiếng Anh (khi đã gắn `t()` trên view) |

## Bundle landing

`nav`, `meta`, `hero`, `common`, `what`, `origin`, `dispute`, `opposition`, `witnesses`, `verdict`, `sovereignty`, `footer` — xem `app/Support/LandingLangBundles.php`.

## AI dịch (tùy chọn)

Trong `.env`: `AI_ENABLED=true`, khóa API theo `config/ai.php` và `config/lang_ui_ai.php`.

## Vite (`public/build`)

Production dùng file đã build sẵn — **commit** `public/build/` (manifest + `assets/*`).

- Mỗi lần đổi `resources/css`, `resources/js`, `resources/sources/style.scss` → chạy `npm run build` rồi commit `public/build`.
- Server **không** cần Node/npm.
- Lỗi manifest: `docs/DEPLOY.md`.

## Asset public (`storage/app/public`)

Ảnh và file tĩnh **được phép commit** lên GitHub (không giống `storage/framework` hay `storage/logs`).

- Đặt file vào `storage/app/public/images/`, `storage/app/public/sounds/`, …
- URL: `https://ninedashline.dev/storage/images/...` (cần `php artisan storage:link` trên server)
- Chi tiết: `storage/app/public/README.md`

```bash
git add storage/app/public
git status   # kiểm tra file ảnh/mp3 xuất hiện
git commit -m "Cập nhật asset storage public"
git push
```

## Lưu ý

- Sau khi lưu bundle trong admin: `php artisan config:clear` (hoặc reload — `AppServiceProvider` nạp lại `lang_ui`).
- `welcome.blade.php` vẫn hardcode tiếng Việt cho đến khi thay chuỗi bằng `t('key')` và bổ sung đủ key trong từng bundle.
