FROM php:8.1.0-fpm

RUN apt-get update \
    && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql bcmath

WORKDIR /var/www/html
