#!/bin/sh
set -e

APP_DIR="/var/www/html"

# attempt to make /tmp world-writable; only root can do this
if [ "$(id -u)" -eq 0 ]; then
  chmod 1777 /tmp
fi

# change ownership only if we are root (bind mounts may prevent it otherwise)
if [ "$(id -u)" -eq 0 ]; then
  chown -R www-data:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache
  chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache
  chmod -R 777 $APP_DIR/storage/framework/sessions
fi

mkdir -p $APP_DIR/storage/app/public
# ownership and permission tweaks only as root
if [ "$(id -u)" -eq 0 ]; then
  chown -R www-data:www-data $APP_DIR/storage/app/public
  chmod -R 775 $APP_DIR/storage/app/public
fi

# Ensure frontend dependencies and build artifacts are present (bind-mounts can hide them)
if command -v npm >/dev/null 2>&1; then
  if [ ! -d "$APP_DIR/node_modules" ] || [ ! -d "$APP_DIR/public/build" ]; then
    echo "Installing npm dependencies and building assets..."
    cd $APP_DIR
    if [ -f package-lock.json ] || [ -f npm-shrinkwrap.json ]; then
      npm ci --legacy-peer-deps
    else
      npm install --legacy-peer-deps
    fi
    npm run build
    chown -R www-data:www-data node_modules public/build
  fi
else
  echo "npm not available in container; skipping frontend build-check in entrypoint"
fi

# Wait until Composer vendor folder is ready
until php -r "require '$APP_DIR/vendor/autoload.php'; exit(0);" > /dev/null 2>&1; do
  echo "Vendor folder missing or database not ready..."
  if [ ! -f "$APP_DIR/vendor/autoload.php" ]; then
    echo "Installing composer dependencies..."
    if [ "${APP_ENV:-production}" != "production" ]; then
      # non-production environments need dev packages such as pail
      composer install --prefer-dist --optimize-autoloader
    else
      composer install --no-dev --prefer-dist --optimize-autoloader
    fi
  fi
  sleep 3
done

cd $APP_DIR

# now regenerate the caches we actually want for production
echo "Caching config and routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# run migrations and seeders only if database hasn't yet been initialised
# (persistent Postgres volume survives compose down/up)
echo "Checking database initialization..."
if ! php -r "require '$APP_DIR/vendor/autoload.php'; \
    \$app=require '$APP_DIR/bootstrap/app.php'; \
    \$kernel=\$app->make(Illuminate\\Contracts\\Console\\Kernel::class); \
    \$kernel->bootstrap(); \
    echo Illuminate\\Support\\Facades\\Schema::hasTable('migrations') ? '1' : '0';" | grep -q 1; then
  echo "Database empty, running initial migrations and seeders..."
  php artisan migrate --force || true
  php artisan db:seed --force || true
else
  echo "Database already initialized; skipping migrate/seed."
fi

echo "Ensuring storage symlink..."
php artisan storage:link || true

echo "Starting Laravel queue worker..."
php artisan queue:work --sleep=3 --tries=3 &

echo "Starting PHP-FPM..."
exec php-fpm -F