# Stage 1: Composer Dependencies
FROM composer:2 AS composer

WORKDIR /app

COPY composer.json composer.lock ./

ENV COMPOSER_NO_INTERACTION=1

RUN composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --ignore-platform-reqs

COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative

# Stage 2: Node.js Build (Vite)
FROM node:22-slim AS node

WORKDIR /app

COPY package.json package-lock.json ./

RUN npm ci

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./

RUN npm run build

# Stage 3: PHP Runtime
FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libxml2-dev \
    sqlite-dev \
    bash \
    nano \
    vim \
    nodejs \
    npm

# Install Composer
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Configure Nginx to run as www-data (align with PHP-FPM)
RUN sed -i 's/user nginx;/user www-data;/g' /etc/nginx/nginx.conf 2>/dev/null || \
    echo "user www-data;" > /etc/nginx/conf.d/user.conf

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
    pdo_sqlite \
    gd \
    zip \
    intl \
    mbstring \
    xml \
    simplexml \
    pcntl \
    posix \
    opcache

# Configure PHP for production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY docker/php/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini
COPY docker/php/uploads.ini $PHP_INI_DIR/conf.d/uploads.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www/html

COPY --chown=www-data:www-data . .

COPY --from=composer --chown=www-data:www-data /app/vendor ./vendor

COPY --from=node --chown=www-data:www-data /app/public/build ./public/build

RUN mkdir -p storage/framework/{sessions,views,cache} \
    storage/logs \
    bootstrap/cache \
    /var/log/supervisor \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

RUN mkdir -p /var/log/php-fpm \
    && touch /var/log/php-fpm/error.log \
    && mkdir -p /var/log/supervisord \
    && touch /var/log/supervisord/supervisord.log \
    && chown -R root:www-data /var/log/php-fpm /var/log/supervisord \
    && chmod -R 775 /var/log/php-fpm /var/log/supervisord \
    && chown -R www-data:www-data /var/lib/nginx \
    && chmod -R 775 /var/lib/nginx

COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

HEALTHCHECK --interval=30s --timeout=3s --start-period=40s --retries=3 \
    CMD wget -q -O /dev/null http://127.0.0.1/health || exit 1

CMD ["/start.sh"]