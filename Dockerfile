FROM php:8.4-fpm-alpine
LABEL authors="BorisMedvedev"

RUN docker-php-ext-install ctype pdo iconv

ENTRYPOINT ["top", "-b"]