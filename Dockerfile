FROM php:8.3-apache

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && \
    apt-get install -y \
    libzip-dev \
    zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip

# Copy your custom Apache virtual host configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set Permissions for Laravel
# Ensure www-data (Apache user) owns storage/cache AFTER code copy & composer install
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache


EXPOSE 80
