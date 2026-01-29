# --- STAGE 1: Build Backend Dependencies (Composer) ---
FROM composer:2.6 as vendor
WORKDIR /app
COPY composer.json composer.lock ./
# Install dependency tanpa dev-dependencies untuk production
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader --no-scripts --ignore-platform-reqs

# --- STAGE 2: Build Frontend Assets (Node.js & Vite) ---
FROM node:20-alpine as assets
WORKDIR /app
COPY package*.json ./
# Install npm dependencies
RUN npm ci
# Copy seluruh source code untuk proses build assets
COPY . .
# Compile assets (hasilnya ada di public/build)
RUN npm run build

# --- STAGE 3: Production Image (Final) ---
FROM php:8.2-apache

# 1. Install System Dependencies & PHP Extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev libzip-dev zip unzip git curl \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Config Apache DocumentRoot ke /public (Standar Laravel)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 3. Aktifkan Mod Rewrite
RUN a2enmod rewrite

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# 4. Copy PHP Config Custom
COPY ./php-custom.ini /usr/local/etc/php/conf.d/php-custom.ini

# 5. COPY Source Code (Teknik Layering Hemat Size)
# Copy source code aplikasi (tanpa vendor/node_modules karena ada di .dockerignore)
# Langsung set owner ke www-data agar tidak perlu chmod -R di akhir (Hemat layer size!)
COPY --chown=www-data:www-data . .

# 6. Copy Hasil Build dari Stage Sebelumnya
# Ambil vendor dari Stage 1
COPY --from=vendor --chown=www-data:www-data /app/vendor ./vendor
# Ambil assets build dari Stage 2
COPY --from=assets --chown=www-data:www-data /app/public/build ./public/build

# 7. Pastikan folder storage bisa ditulis (Safety Check)
RUN chmod -R 775 storage bootstrap/cache