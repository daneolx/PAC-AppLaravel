# PHP Stage
FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    wget \
    icu-dev \
    libxml2-dev \
    libpng-dev \
    libzip-dev \
    oniguruma-dev \
    bash \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    intl \
    bcmath \
    gd \
    zip \
    pcntl \
    opcache

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Install NPM dependencies and build assets
RUN npm install && npm run build

# Clear existing storage logs and cache
RUN rm -rf storage/logs/*.log \
    && rm -rf storage/framework/cache/data/* \
    && rm -rf storage/framework/sessions/* \
    && rm -rf storage/framework/views/*.php

# Set permissions
RUN chown -R www-data:www-data storage bootstrap/cache public database

# Configuration
COPY nginx.conf /etc/nginx/http.d/default.conf

# Expose port
EXPOSE 80

# Set environment variables
ENV LOG_CHANNEL=stderr

# Start script
CMD php artisan migrate --force \
    && php artisan db:seed --force \
    && chown -R www-data:www-data database \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php-fpm -D \
    && nginx -g "daemon off;"
