FROM php:8.1-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    libonig-dev \
    libsqlite3-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip \
    && a2enmod rewrite \
    && printf "<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n" > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --no-progress --optimize-autoloader --no-scripts

COPY . .

RUN composer dump-autoload --optimize --no-dev \
    && php artisan package:discover --ansi

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwx storage bootstrap/cache \
    && sed -ri -e 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

COPY docker/start.sh /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

EXPOSE 10000

CMD ["start-container"]
