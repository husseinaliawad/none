#!/usr/bin/env bash
set -e

PORT="${PORT:-10000}"
DB_CONNECTION="${DB_CONNECTION:-sqlite}"
FORCE_SQLITE="${FORCE_SQLITE:-true}"

mkdir -p storage/logs storage/framework/cache storage/framework/sessions storage/framework/views bootstrap/cache

if [ -z "${APP_KEY:-}" ]; then
  export APP_KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
  echo "APP_KEY is not set. Generated a temporary runtime key for this instance."
fi

if [ "${FORCE_SQLITE}" = "true" ] || [ "${DB_CONNECTION}" = "sqlite" ]; then
  export DB_CONNECTION="sqlite"
  unset DATABASE_URL
  unset DB_HOST
  unset DB_PORT
  unset DB_USERNAME
  unset DB_PASSWORD
  unset DB_SOCKET

  SQLITE_PATH="${DB_DATABASE:-/var/www/html/database/database.sqlite}"
  export DB_DATABASE="${SQLITE_PATH}"
  mkdir -p "$(dirname "${SQLITE_PATH}")"
  touch "${SQLITE_PATH}"
fi

sed -i "s/Listen 80/Listen ${PORT}/" /etc/apache2/ports.conf
sed -ri "s/:80>/:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

php artisan storage:link || true
php artisan config:clear || true
php artisan cache:clear || true
php artisan optimize || true

if [ "${RUN_MIGRATIONS:-true}" = "true" ]; then
  php artisan migrate --force
fi

exec apache2-foreground
