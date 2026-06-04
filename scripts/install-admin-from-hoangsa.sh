#!/usr/bin/env bash
# Copy admin UI từ hoangsa.dev → ninedashline.dev (SCSS + view snippets)
set -euo pipefail
SRC="$(cd "$(dirname "$0")/../.." && pwd)/hoangsa.dev"
DST="$(cd "$(dirname "$0")/.." && pwd)"

if [[ ! -d "$SRC/resources/sources/admin" ]]; then
  echo "Không tìm thấy $SRC — chạy từ repo html/" >&2
  exit 1
fi

mkdir -p "$DST/resources/sources"
cp -r "$SRC/resources/sources/admin/"* "$DST/resources/sources/"
cp "$SRC/config/admin.php" "$DST/config/"
mkdir -p "$DST/resources/views/admin/modal" "$DST/resources/views/admin/partials" "$DST/resources/views/admin/components"
cp "$SRC/resources/views/admin/modal/fullLoading.blade.php" "$DST/resources/views/admin/modal/"
cp "$SRC/resources/views/admin/partials/"*.blade.php "$DST/resources/views/admin/partials/"
cp "$SRC/resources/views/admin/components/pageHeader.blade.php" "$DST/resources/views/admin/components/"

echo "OK: admin assets copied to $DST"
echo "Tiếp theo: cd $DST && npm install && npm run build && composer dump-autoload && php artisan migrate --force"
