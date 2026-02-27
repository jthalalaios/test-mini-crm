#!/bin/sh
set -e

APP_DIR="/var/www/html"

chmod 1777 /tmp

chown -R www-data:www-data $APP_DIR/storage $APP_DIR/bootstrap/cache
chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache
chmod -R 777 $APP_DIR/storage/framework/sessions

mkdir -p $APP_DIR/storage/app/public
chown -R www-data:www-data $APP_DIR/storage/app/public
chmod -R 775 $APP_DIR/storage/app/public

# Wait until Composer vendor folder is ready
until php -r "require '$APP_DIR/vendor/autoload.php'; exit(0);" > /dev/null 2>&1; do
  echo "Vendor folder missing or database not ready..."
  if [ ! -f "$APP_DIR/vendor/autoload.php" ]; then
    echo "Installing composer dependencies..."
    composer install --no-dev --prefer-dist --optimize-autoloader
  fi
  sleep 3
done

cd $APP_DIR

echo "Caching config and routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

echo "Running migrations..."
php artisan migrate --force || true

echo "Ensuring storage symlink..."
php artisan storage:link || true


echo "Starting Laravel queue worker..."
php artisan queue:work --sleep=3 --tries=3 &

echo "Starting PHP-FPM..."
exec php-fpm -F