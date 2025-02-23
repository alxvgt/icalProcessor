FROM php:8.1.19-apache

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
    libxml2-dev \
    libcurl4-openssl-dev \
    libssl-dev

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    zip \
    opcache \
    curl \
    sockets

# Install Xdebug (optional, for debugging purposes)
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI
RUN curl -sS https://get.symfony.com/cli/installer | bash
RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Copy sources to image
ADD ./icalProcessor /var/www/html
ADD ./apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# Set the ServerName globally to suppress the FQDN warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
# Configure Apache
RUN a2enmod rewrite

# Install dependencies
ENV COMPOSER_ALLOW_SUPERUSER 1
RUN composer install
ENV COMPOSER_ALLOW_SUPERUSER 0

# Configurer les permissions
RUN mkdir -p /var/www/html/var /var/www/html/vendor /var/www/html/public
RUN chown -R www-data:www-data /var/www/html/var /var/www/html/vendor /var/www/html/public
RUN chmod -R 755 /var/www/html/var /var/www/html/vendor /var/www/html/public

# Démarrer Apache en mode frontal
CMD ["apache2-foreground"]