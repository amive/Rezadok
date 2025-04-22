# Use the official PHP image with Apache
FROM php:8.1-apache

# Install required extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Enable Apache mod_rewrite (if needed)
RUN a2enmod rewrite

# Copy your application code to the container
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/

# Expose port 80
EXPOSE 80