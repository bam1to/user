FROM php:8.4-fpm-alpine AS builder
LABEL authors="BorisMedvedev"

ENV APP_ENV=dev
ENV APP_DEBUG=0

# Build
RUN apk add --no-cache postgresql-dev bash icu-dev libzip-dev oniguruma-dev linux-headers $PHPIZE_DEPS && \
    docker-php-ext-install ctype pdo pdo_pgsql intl zip opcache

RUN if [ "$APP_ENV" = "dev" ]; then \
    pecl install xdebug && \
    docker-php-ext-enable xdebug; \
    fi

WORKDIR /app

COPY composer.json composer.lock symfony.lock ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN composer install --prefer-dist --no-scripts --no-progress;

COPY . .

RUN composer dump-autoload --optimize --classmap-authoritative;

WORKDIR /app

RUN chown -R www-data:www-data /app
USER www-data

EXPOSE 9000

ENTRYPOINT ["/app/entrypoint.sh"]

CMD ["php-fpm"]