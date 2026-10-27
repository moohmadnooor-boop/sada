#!/bin/sh
# Container entrypoint for Render: prepare config, migrate, seed once, then serve.
set -e

# Render's generated secret is 32 random bytes in base64; Laravel expects the "base64:" prefix.
case "$APP_KEY" in
    base64:*) ;;
    "") echo "APP_KEY is not set" >&2; exit 1 ;;
    *) export APP_KEY="base64:$APP_KEY" ;;
esac

export APP_URL="${APP_URL:-$RENDER_EXTERNAL_URL}"

if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    touch database/database.sqlite
fi

php artisan migrate --force
php artisan app:seed-if-empty
php artisan config:cache
php artisan route:cache
php artisan view:cache

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
