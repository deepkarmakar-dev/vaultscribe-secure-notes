FROM php:8.2-cli

WORKDIR /app

# System dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    git \
    libzip-dev \
    zip

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql zip

# Composer install
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy project
COPY . .

# Install dependencies
RUN composer install

# Laravel permissions
RUN chmod -R 777 storage bootstrap/cache

EXPOSE 5000

CMD php artisan serve --host=0.0.0.0 --port=5000
