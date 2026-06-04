#!/usr/bin/env bash
# Một lần: gỡ public/storage khỏi Git index (giữ file/symlink trên disk).
set -euo pipefail
cd "$(dirname "$0")/.."

if git rev-parse --is-inside-work-tree >/dev/null 2>&1; then
  if git ls-files --error-unmatch public/storage >/dev/null 2>&1; then
    git rm -r --cached public/storage
    echo "Đã gỡ public/storage khỏi index. Commit: git commit -m \"Stop tracking public/storage symlink\""
  else
    echo "public/storage không còn trong index — OK."
  fi
else
  echo "Không phải git repo."
  exit 1
fi
