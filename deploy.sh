#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

started=$(date +%s)
git pull --ff-only
docker compose build
docker compose up -d --remove-orphans

artisan() { docker compose exec -T -u www-data web php artisan "$@"; }
artisan migrate --force
artisan optimize
artisan deploy:record --commit="$(git rev-parse HEAD)" --message="$(git log -1 --format=%s)" --duration=$(( $(date +%s) - started ))
