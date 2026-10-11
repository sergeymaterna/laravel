# 1. Беремо готовий офіційний образ PHP з веб-сервером Apache
FROM php:8.5-apache

# 2. Встановлюємо Composer всередину образу
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Вказуємо робочу директорію всередині контейнера
WORKDIR /var/www/html

# 4. Копіюємо всі файли проєкту в контейнер
COPY . .

# 5. Встановлюємо залежності без інструментів розробки
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# 6. Дозволяємо Apache працювати з файлами
RUN chown -R www-data:www-data /var/www/html