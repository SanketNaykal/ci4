# Dockerfile for CodeIgniter 4
FROM php:8.2-apache

# system deps + php ext for CI4
RUN apt-get update && apt-get install -y \
    git zip unzip libzip-dev libpng-dev libonig-dev \
    && docker-php-ext-install pdo_mysql mbstring zip gd \
    && rm -rf /var/lib/apt/lists/*

# enable apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# copy composer files first for caching
COPY composer.json composer.lock ./

# install composer and dependencies
RUN php -r "copy('https://getcomposer.org/installer','composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist \
 && rm -f composer-setup.php

# copy the rest of the app
COPY . .

# set Apache document root to CI4 public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# ensure writable directory permissions
RUN chown -R www-data:www-data /var/www/html/writable \
 && chmod -R 775 /var/www/html/writable

EXPOSE 80
CMD ["apache2-foreground"]