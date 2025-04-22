# Use the official PHP image with Apache
FROM php:8.1-apache

# Install required extensions and dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite

# Copy your application code to the container
COPY . /var/www/html/

# Set the Apache document root to the directory containing index.php
ENV APACHE_DOCUMENT_ROOT /var/www/html/api

# Update the Apache configuration to use the new document root
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Restart Apache to apply changes
RUN service apache2 restart

# Expose port 80
EXPOSE 80