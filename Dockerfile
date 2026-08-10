# Use an official PHP 5.6 image with Apache
FROM php:5.6-apache

# Add custom sources.list (point to the archived repositories for Debian Stretch)
COPY ./docker/custom.source.list /etc/apt/sources.list

# Install dependencies for PostgreSQL extension, mcrypt, zip, and nano
RUN apt-get update && apt-get install -y --allow-unauthenticated \
    libpq-dev \
    libmcrypt-dev \
    libzip-dev \
    nano \
    && docker-php-ext-install pdo_pgsql pgsql mcrypt zip calendar \
    && apt-get clean

# Enable mod_rewrite for Apache (if needed)
RUN a2enmod rewrite

# Set working directory in the container
WORKDIR /var/www/html

# Copy your PHP application into the container
COPY . /var/www/html

# Expose port 80 to the host machine
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
