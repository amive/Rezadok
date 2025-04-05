# Expose port 80
EXPOSE 80

FROM php:8.2-apache

# Install PostgreSQL client libraries and PHP extensions
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Optional: Apache rewrite if you're doing routing
RUN a2enmod rewrite

# Copy project files
COPY . /var/www/html/

# Set working dir (optional but clean)
WORKDIR /var/www/html
