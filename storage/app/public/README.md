# Public storage (commit lên Git)

Thư mục này được phục vụ qua URL `/storage/...` (symlink `public/storage` → đây).

| Thư mục | Ví dụ |
|---------|--------|
| `images/` | Ảnh landing, favicon, OG |
| `sounds/` | `hello-viet-nam.mp3` (xem `config/landing.php` → `ambient_audio`) |

Sau khi thêm file:

```bash
git add storage/app/public
git commit -m "Cập nhật asset public storage"
git push
```

Trên server sau `git pull`:

```bash
php artisan storage:link --force
chown -R www:www storage/app/public
find storage/app/public -type d -exec chmod 755 {} \;
find storage/app/public -type f -exec chmod 644 {} \;
```

Không commit file tạm (`.DS_Store`, `*.tmp`) — đã loại trong `.gitignore` cục bộ.
