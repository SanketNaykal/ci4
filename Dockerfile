# Dockerfile for CodeIgniter 4 on Render (PHP 8.2 + PostgreSQL).
FROM php:8.2-apache

# Install system packages needed to build PHP extensions (including libpq-dev)
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -y --no-install-recommends \
    git \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libpng-dev \
    libicu-dev \
    libpq-dev \            # <<-- provides libpq-fe.h and pg_config
    postgresql-client \    # helpful for psql & debugging (optional)
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    zlib1g-dev \
  && docker-php-ext-configure gd --with-freetype --with-jpeg \
  && docker-php-ext-install -j$(nproc) pdo_pgsql pgsql mbstring zip intl gd \
  && apt-get purge -y --auto-remove git unzip \
  && rm -rf /var/lib/apt/lists/*

# Enable Apache rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

# Copy composer files first (cache)
COPY composer.json composer.lock ./

# Install composer and PHP deps
RUN php -r "copy('https://getcomposer.org/installer','composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && composer install --no-dev --no-interaction --optimize-autoloader --prefer-dist \
 && rm -f composer-setup.php

# Copy app files
COPY . .

# Set Apache DocumentRoot to CodeIgniter public folder
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Fix writable dir permissions
RUN chown -R www-data:www-data /var/www/html/writable \
 && chmod -R 775 /var/www/html/writable

EXPOSE 80
CMD ["apache2-foreground"]
