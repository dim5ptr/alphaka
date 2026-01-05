FROM php:8.3-fpm-bookworm

# Install dependencies sistem + Nginx
RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        exif \
        pcntl \
        bcmath \
        gd \
        zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy konfigurasi Nginx (pastikan file nginx.conf kamu sudah benar!)
COPY ./nginx.conf /etc/nginx/nginx.conf

# Set working directory
WORKDIR /var/www/html

# Copy aplikasi (lebih baik copy composer files dulu agar cache bagus)
COPY composer.json composer.lock* ./
RUN composer install --no-dev --optimize-autoloader --no-scripts --no-interaction

# Copy sisa file aplikasi
COPY . .

# Permission Laravel (lebih aman)
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 storage bootstrap/cache \
    && find storage bootstrap/cache -type f -exec chmod 644 {} \; \
    && find storage bootstrap/cache -type d -exec chmod 755 {} \;

# Expose port yang benar (Nginx default 80)
EXPOSE 80

# Jalankan keduanya dengan cara yang lebih reliable
# (foreground mode wajib di container!)
CMD ["sh", "-c", "nginx -g 'daemon off;' & php-fpm"]
