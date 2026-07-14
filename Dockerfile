
FROM php:8.4-apache

# Install all the necessary system dependencies (libxml2-dev is required for XML extensions)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip

# Install the PHP extensions Laravel needs (dom, xml, and xmlwriter have been added)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip dom xml xmlwriter

# Enable Apache mod_rewrite (required for Laravel's routing to work)
RUN a2enmod rewrite
    
# Automatically update the Apache DocumentRoot to Laravel's public folder
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Download and install Composer inside the container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Give Apache permission to modify files (crucial for storage and cache)
RUN chown -R www-data:www-data /var/www/html