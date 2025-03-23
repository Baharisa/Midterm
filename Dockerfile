# Use the official PHP image with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install system dependencies for PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    unzip \
    zip \
    && docker-php-ext-install pdo pdo_pgsql

# Copy all project files to the Apache web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Optional: Set permissions if needed
RUN chown -R www-data:www-data /var/www/html

# Expose port (Apache default)
EXPOSE 80
