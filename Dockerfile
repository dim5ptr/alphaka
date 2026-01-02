# Gunakan base image PHP dengan FPM dan Nginx
FROM php:8.3-fpm

# Install Nginx
RUN apt-get update && apt-get install -y nginx

# Copy file konfigurasi Nginx
COPY ./nginx.conf /etc/nginx/nginx.conf

# Install ekstensi PHP yang diperlukan Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy semua file Laravel ke dalam container
COPY . /var/www/html

# Set permission untuk folder Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Expose port 80 untuk Nginx
EXPOSE 8406

# Script entrypoint untuk menjalankan Nginx dan PHP-FPM
CMD service nginx start && php-fpm
