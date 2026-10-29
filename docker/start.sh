#!/bin/sh
# Container entrypoint for Render: prepare config, migrate, seed once, then serve.
set -e

# Laravel needs a 32-byte key with a "base64:" prefix. Render generates a random secret of its own
# format, so derive a valid key from it (SHA-256 always gives exactly 32 bytes).
case "$APP_KEY" in
    base64:*) ;;
    "") echo "APP_KEY is not set" >&2; exit 1 ;;
    *) APP_KEY=$(php -r 'echo "base64:".base64_encode(hash("sha256", getenv("APP_KEY"), true));'); export APP_KEY ;;
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
