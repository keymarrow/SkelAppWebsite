#!/bin/bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/skelapp.tz}"

echo "🚀 Deploying SkelAppWebsite..."

cd "$APP_DIR"

artisan_boots() {
  php artisan --version >/dev/null 2>&1
}

bring_app_up() {
  if artisan_boots; then
    php artisan up || true
  else
    echo "⚠️  Skipping 'php artisan up' because the app cannot bootstrap."
  fi
}

MAINTENANCE_ENABLED=0

# Always try to bring the app back up if we successfully enabled maintenance mode.
trap 'if [ "$MAINTENANCE_ENABLED" -eq 1 ]; then bring_app_up; fi' EXIT

# 1. Put site into maintenance mode.
if artisan_boots; then
  php artisan down --refresh=15
  MAINTENANCE_ENABLED=1
else
  echo "⚠️  Current release cannot bootstrap artisan; syncing code before maintenance mode."
fi

# 2. Sync server code exactly to GitHub.
git fetch origin
git reset --hard origin/main

# 3. Install/update PHP dependencies.
composer install \
  --no-dev \
  --no-interaction \
  --prefer-dist \
  --optimize-autoloader

# If the old release could not enter maintenance mode, do it now that the
# updated code and autoloader are in place.
if [ "$MAINTENANCE_ENABLED" -eq 0 ]; then
  php artisan down --refresh=15
  MAINTENANCE_ENABLED=1
fi

# 4. Run database migrations.
php artisan migrate --force

# 5. Clear stale caches, then rebuild production caches.
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 6. Fix writable directories.
chown -R www-data:www-data storage bootstrap/cache

# 7. Bring site back up.
php artisan up
MAINTENANCE_ENABLED=0
trap - EXIT

echo "✅ Deployment complete!"
