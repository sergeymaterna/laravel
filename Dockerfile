FROM php:8.5-apache

# Встановлюємо системні залежності включно з бібліотекою для PostgreSQL (libpq-dev)
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    unzip

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Встановлюємо PHP розширення для PostgreSQL (замість MySQL)
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Вмикаємо модуль Apache Rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

# Вказуємо публічну папку Laravel як корень для Apache
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Встановлюємо залежності без зайвих скриптів на етапі білду
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Налаштовуємо права на папки
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache