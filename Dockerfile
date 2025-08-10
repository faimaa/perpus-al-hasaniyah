# Use PHP 7.4 (compatible with CodeIgniter 3)
FROM php:7.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mysqli \
    && docker-php-ext-install zip \
    && docker-php-ext-install pdo_mysql

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Create necessary directories and set permissions
RUN mkdir -p /var/www/html/application/cache \
    && mkdir -p /var/www/html/application/logs \
    && mkdir -p /var/www/html/application/sessions \
    && chown -R www-data:www-data /var/www/html/application/cache \
    && chown -R www-data:www-data /var/www/html/application/logs \
    && chown -R www-data:www-data /var/www/html/application/sessions \
    && chmod -R 777 /var/www/html/application/cache \
    && chmod -R 777 /var/www/html/application/logs \
    && chmod -R 777 /var/www/html/application/sessions

# Copy Apache configuration
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

# Set production environment
ENV CI_ENVIRONMENT=production

# Railway MySQL Environment Variables (will be overridden by Railway)
ENV MYSQLHOST="mysql.railway.internal"
ENV MYSQLPORT="3306"
ENV MYSQLDATABASE="railway"
ENV MYSQLUSER="root"
ENV MYSQLPASSWORD="bVtkQHAqbFKxGoMuBoMslpIEaJogYtzv"

# Expose port
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"] 