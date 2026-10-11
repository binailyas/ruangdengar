FROM php:8.2-fpm

# Dependensi sistem dan ekstensi PHP
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# CSS Tailwind v4 sudah dibangun & di-commit di public/css/app.css (dimuat lewat asset(),
# bukan @vite), jadi TIDAK ada langkah npm/vite di sini.
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Izin tulis storage & cache
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Render mengisi $PORT (default 10000); fallback 8080 untuk lokal
EXPOSE 10000

# RUN_SEED=true (env Render) -> jalankan db:seed saat startup (data dasar; + data demo bila APP_DEMO=true).
# Berjalan sebelum config:cache. Aman diulang: data demo hanya dibuat jika tabel users masih kosong.
CMD ["sh", "-c", "php artisan migrate --force && if [ \"$RUN_SEED\" = \"true\" ]; then php artisan db:seed --force; fi && php artisan config:cache && php artisan route:cache && php artisan view:cache && php artisan serve --host=0.0.0.0 --port=${PORT:-8080}"]
