#!/bin/bash
set -e

REPO="$(cd "$(dirname "$0")" && pwd)"
ROOT="$(cd "$REPO/.." && pwd)"

ADMIN_SOURCE="$REPO/admin.tyres4sale.com"
TRADE_SOURCE="$REPO/tradeconnect.tyres4sale.com"

ADMIN_LIVE="$ROOT/admin.tyres4sale.com"
TRADE_LIVE="$ROOT/tradeconnect.tyres4sale.com"

# Safety checks - abort rather than deploy somewhere unexpected
[ -d "$ADMIN_SOURCE" ] || { echo "Admin source missing"; exit 1; }
[ -d "$TRADE_SOURCE" ] || { echo "Trade source missing"; exit 1; }
[ -d "$ADMIN_LIVE" ] || { echo "Live admin directory missing"; exit 1; }
[ -d "$TRADE_LIVE" ] || { echo "Live trade directory missing"; exit 1; }

echo "Deploying Admin..."
rsync -a \
  --exclude='functions/db.php' \
  --exclude='functions/db.php.*' \
  "$ADMIN_SOURCE/" "$ADMIN_LIVE/"

echo "Deploying Trade Connect..."
rsync -a \
  --exclude='functions/db.php' \
  --exclude='functions/db.php.*' \
  --exclude='config/database.php' \
  --exclude='app/Db/Db.php' \
  "$TRADE_SOURCE/" "$TRADE_LIVE/"

echo "Tyres4sale deployment completed successfully."