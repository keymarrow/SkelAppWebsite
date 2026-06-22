#!/bin/bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/skelapp.tz}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-main}"

# Allow Composer to run as root (this script deploys as root) without prompting.
export COMPOSER_ALLOW_SUPERUSER=1

echo "🚀 Deploying SkelAppWebsite ($DEPLOY_BRANCH)..."

cd "$APP_DIR"

# Git refuses to operate on a repo owned by another user ("dubious ownership").
# The webroot is owned by www-data, so mark it safe for the deploy user (root).
git config --global --add safe.directory "$APP_DIR" 2>/dev/null || true

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
git fetch origin --prune
git reset --hard "origin/$DEPLOY_BRANCH"

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

# 5. Ensure CMS page records exist and backfill any new default fields.
php artisan db:seed --class=PagesSeeder --force

# 6. Clear stale caches, then rebuild production caches.
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Fix writable directories.
chown -R www-data:www-data storage bootstrap/cache

# 8. Bring site back up.
php artisan up
MAINTENANCE_ENABLED=0
trap - EXIT

echo "✅ Deployment complete!🚀"
