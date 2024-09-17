# Use a base PHP image with necessary extensions
FROM php:8.3-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev \
    libzip-dev unzip git nginx \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip

# Set up Nginx configuration
COPY nginx/default.conf /etc/nginx/conf.d/default.conf

# Set up the Laravel application
WORKDIR /var/www/html
COPY . .

# Set permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage

# Expose the ports
EXPOSE 80 9000

# Start Nginx and PHP-FPM
CMD ["sh", "-c", "service nginx start && php-fpm"]
