# Use PHP with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PDO and PostgreSQL driver
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pdo pdo_pgsql

# Copy project files into the Apache root
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Optional: Expose port (Render handles this automatically)
EXPOSE 80
