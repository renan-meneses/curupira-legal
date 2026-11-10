FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
        bash \
        postgresql-dev \
        libzip-dev \
        oniguruma-dev \
        icu-dev \
        unzip \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql mbstring intl opcache \
    && rm -rf /var/cache/apk/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-progress

COPY . .
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

USER www-data
EXPOSE 9000
CMD ["php-fpm", "-F"]
