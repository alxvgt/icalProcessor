FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    unzip \
    zip \
    libonig-dev \
    libxml2-dev

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    zip \
    opcache

# Install Xdebug (optional, for debugging purposes)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Set working directory
WORKDIR /var/www/html

# Set proper permissions for the Symfony application
#RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor /var/www/html/public
#RUN chmod -R 755 /var/www/html/var /var/www/html/vendor /var/www/html/public

# Expose port 9000 and start PHP-FPM server
EXPOSE 9000
CMD ["php-fpm"]
