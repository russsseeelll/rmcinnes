FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json vite.config.js ./
RUN npm ci
COPY resources resources
RUN npm run build

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs --no-interaction
COPY . .
RUN composer dump-autoload --optimize --no-dev --no-scripts

FROM php:8.4-apache
RUN echo 'Acquire::Retries "5";' > /etc/apt/apt.conf.d/80-retries \
    && apt-get update \
    && apt-get install -y --no-install-recommends libzip-dev libicu-dev \
    && docker-php-ext-install pdo_mysql intl zip bcmath pcntl opcache \
    && apt-get install -y --no-install-recommends $PHPIZE_DEPS \
    && pecl install opentelemetry \
    && docker-php-ext-enable opentelemetry \
    && apt-get purge -y --auto-remove $PHPIZE_DEPS \
    && a2enmod rewrite headers \
    && rm -rf /var/lib/apt/lists/*
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri 's!AllowOverride None!AllowOverride All!g' /etc/apache2/apache2.conf \
    && echo 'ServerTokens Prod\nServerSignature Off' > /etc/apache2/conf-enabled/zz-hardening.conf \
    && cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && printf 'upload_max_filesize=12M\npost_max_size=14M\nexpose_php=Off\n' > "$PHP_INI_DIR/conf.d/app.ini"
WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build public/build
RUN mkdir -p public/storage/images public/storage/cv storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && php artisan package:discover --ansi \
    && chown -R www-data:www-data storage bootstrap/cache public/storage
