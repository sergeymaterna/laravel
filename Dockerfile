FROM php:8.5-apache

# Встановлюємо системні залежності та розширення для Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# Очищуємо кеш apt
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Встановлюємо PHP розширення (pdo_mysql, bcmath, gd тощо)
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Встановлюємо Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Вмикаємо модуль Apache Rewrite (обов'язково для Laravel)
RUN a2enmod rewrite

# Вказуємо робочу директорію
WORKDIR /var/www/html

# Копіюємо весь проєкт
COPY . .

# Вказуємо публічну папку Laravel як корень для Apache
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Встановлюємо залежності composer без dev-пакетів
RUN composer install --no-dev --optimize-autoloader

# Налаштовуємо права на папки storage та bootstrap/cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache