FROM php:8.2-apache

# Install dependencies for PostgreSQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# Enable Apache rewrite module
RUN a2enmod rewrite

# Copy your app files into the container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html
