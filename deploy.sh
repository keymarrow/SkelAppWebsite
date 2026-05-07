#!/bin/bash
set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/skelapp.tz}"

echo "🚀 Deploying SkelAppWebsite..."

cd "$APP_DIR"

# Always bring the app back up, even if a later step fails.
trap 'php artisan up || true' EXIT

# 1. Put site into maintenance mode.
php artisan down --refresh=15

# 2. Sync server code exactly to GitHub.
git fetch origin
git reset --hard origin/main

# 3. Install/update PHP dependencies.
composer install \
  --no-dev \
  --no-interaction \
  --prefer-dist \
  --optimize-autoloader

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
trap - EXIT

echo "✅ Deployment complete!"
