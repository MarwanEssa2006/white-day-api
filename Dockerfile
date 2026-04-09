FROM php:8.2-fpm-alpine

# تثبيت الإضافات اللازمة لـ Laravel
RUN docker-php-ext-install pdo pdo_mysql

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
COPY . .

# تثبيت المكتبات
RUN composer install --no-dev --optimize-autoloader

# تشغيل السيرفر
CMD php artisan serve --host=0.0.0.0 --port=10000
