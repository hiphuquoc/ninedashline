# Thư mục `public/`

## Deploy không chạy `npm` trên server

Build **trên máy dev**, rồi commit cả `public/build/`:

```bash
npm ci
npm run build
git add public/build
git commit -m "Build Vite assets for production"
git push
```

Server chỉ cần `git pull` — phải có `public/build/manifest.json`.

## Không commit

| Path | Lý do |
|------|--------|
| `public/hot` | Vite dev server |
| `public/storage` | Symlink → `storage/app/public` (`php artisan storage:link`) |

## Sau deploy

```bash
php artisan storage:link
# Không cần: npm install, npm run build
```
