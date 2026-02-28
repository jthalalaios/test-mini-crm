# =========================
# Stage 1: Build frontend assets (Node + Vite)
# =========================
FROM node:20 AS node_builder

WORKDIR /app

COPY laravel/package*.json ./

RUN if [ -f package-lock.json ] || [ -f npm-shrinkwrap.json ]; then \
      npm ci --legacy-peer-deps; \
    else \
      npm install --legacy-peer-deps; \
    fi

COPY laravel/resources ./resources
COPY laravel/vite.config.js ./
COPY laravel/tailwind.config.js ./
RUN npm run build

FROM php:8.4-fpm AS composer_builder

WORKDIR /app

# Install system dependencies for Laravel & PHP extensions
RUN apt-get update && apt-get install -y \
    git unzip zip curl libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring bcmath exif pcntl gd zip \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy Laravel code
COPY laravel/ .

# Install Composer dependencies (production)
RUN composer install --no-dev --prefer-dist --optimize-autoloader \
    && composer dump-autoload --optimize

# =========================
# Stage 3: Final PHP-FPM image
# =========================
FROM php:8.4-fpm

WORKDIR /var/www/html

# Install PHP extensions and dependencies
RUN apt-get update && apt-get install -y \
    git curl unzip zip \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev \
    libpq-dev \
    nodejs npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo pdo_pgsql pgsql mbstring bcmath exif pcntl gd zip \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy application code
COPY laravel/ /var/www/html

# delete any pre-generated config/packages caches that might have been
# committed to the repo; we always rebuild them at runtime so the
# container should start with a clean slate.
RUN rm -rf /var/www/html/bootstrap/cache/* || true

# Copy vendor from Composer stage
COPY --from=composer_builder /app/vendor ./vendor

# Copy built frontend assets
COPY --from=node_builder /app/public/build ./public/build

# Set Laravel storage/framework and bootstrap/cache permissions
RUN mkdir -p storage/framework/{views,cache,sessions} bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chmod -R 777 storage/framework/sessions \
    && chmod 1777 /tmp

# Optional: set PHP system temp dir explicitly
RUN echo "sys_temp_dir=/tmp" >> /usr/local/etc/php/conf.d/temp.ini

COPY configuration/app_conf/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose PHP-FPM port
EXPOSE 9000

# Use entrypoint
ENTRYPOINT ["entrypoint.sh"]
