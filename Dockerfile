# Use official PHP image
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PDO and PostgreSQL extensions
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Copy all project files into the Apache web root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Optional: Set proper permissions
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
