# Use the official PHP image from Docker Hub
FROM php:8.0-apache

# Install PostgreSQL extensions
RUN docker-php-ext-install pdo pdo_pgsql pgsql

# Enable mod_rewrite for Apache (if needed)
RUN a2enmod rewrite

# Copy your custom php.ini file into the container
COPY php.ini /usr/local/etc/php/

# Set the working directory inside the container
WORKDIR /var/www/html

# Copy the current directory (your code) to the container's working directory
COPY . .

# Expose port 80
EXPOSE 80
