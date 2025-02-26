FROM php:8.3-fpm

# Установите необходимые расширения PHP
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

WORKDIR /var/www/html

COPY .. .

CMD ["php-fpm"]
