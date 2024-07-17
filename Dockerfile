FROM php:8.3-fpm

# Install necessary packages including netcat
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    ncat \
    && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install zip pdo_mysql

WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Composer dependencies
RUN composer install

# Copy entrypoint script into container
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Expose port and set command to wait for db and run migrations
EXPOSE 8000
#CMD ["./wait-for-it.sh", "db:3306", "--", "php", "artisan", "migrate", "--force", "&&", "php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
ENTRYPOINT ["docker-entrypoint.sh"]