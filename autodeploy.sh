#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"

exec 9>/tmp/rmcinnes-autodeploy.lock
flock -n 9 || exit 0

git fetch -q origin main
current=$(git rev-parse HEAD)
latest=$(git rev-parse origin/main)
[ "$current" = "$latest" ] && exit 0

repo=$(git remote get-url origin | sed -E 's#(git@github.com:|https://github.com/)##; s#\.git$##')
verdict=$(curl -fsS -H "Accept: application/vnd.github+json" "https://api.github.com/repos/$repo/commits/$latest/check-runs" | python3 -c '
import json, sys
runs = json.load(sys.stdin).get("check_runs", [])
if not runs or any(r["status"] != "completed" for r in runs):
    print("pending")
elif all(r["conclusion"] in ("success", "skipped", "neutral") for r in runs):
    print("passed")
else:
    print("failed")
')

case "$verdict" in
    passed) echo "$(date -Is) deploying ${latest:0:7}"; ./deploy.sh ;;
    failed) echo "$(date -Is) skipping ${latest:0:7}: checks failed" ;;
esac
