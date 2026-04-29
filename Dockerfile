FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip default-mysql-client \
    && docker-php-ext-install pdo pdo_mysql zip pcntl

RUN pecl install redis && docker-php-ext-enable redis

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_HOME=/tmp/composer

WORKDIR /var/www/html

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh                                                                                                                                
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["entrypoint.sh"]
CMD ["php-fpm", "-F"]