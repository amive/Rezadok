FROM php:8.0-apache

# Install dependencies needed to build PostgreSQL extensions
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Enable mod_rewrite if needed
RUN a2enmod rewrite

# Optional: Copy custom PHP config
COPY php.ini /usr/local/etc/php/

# Set working directory
WORKDIR /var/www/html

# Copy project files
COPY . .

# Expose port 80
EXPOSE 80
