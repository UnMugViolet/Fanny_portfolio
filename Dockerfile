# Multi-stage Dockerfile for Laravel with Vue.js and Inertia.js

# ================================
# Development Stage
# ================================
FROM php:8.3-fpm AS development

# Set working directory
WORKDIR /var/www/html

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    build-essential \
    curl \
    git \
    gosu \
    jpegoptim \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    locales \
    optipng \
    pngquant \
    unzip \
    vim \
    zip \
    supervisor \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Install Node.js 22.x LTS (required for Tailwind v4 and latest packages)
RUN curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy package files first for better caching
COPY package*.json ./
COPY composer.json composer.lock ./

COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-interaction \
    && npm install \
    && npm audit fix --force || true

# Copy and set up startup scripts
COPY docker/scripts/laravel-start.sh /usr/local/bin/laravel-start.sh
RUN chmod +x /usr/local/bin/laravel-start.sh

# Copy Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["/usr/local/bin/laravel-start.sh"]

# ================================
# Production Stage
# ================================
FROM php:8.3-fpm AS production

# Set working directory
WORKDIR /var/www/html

# Install system dependencies (minimal for production) and PHP extensions
RUN apt-get update && apt-get install -y \
    curl \
    git \
    gosu \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    nginx \
    supervisor \
    unzip \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Copy Supervisor configuration
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application code first
COPY . .

# Install PHP dependencies (production only)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Install ALL npm dependencies for building (including dev dependencies)
RUN npm ci

# Build frontend assets and cleanup
RUN npm run build \
    && rm -rf node_modules \
    && mv /var/www/html/public/build/.vite/manifest.json /var/www/html/public/build/manifest.json \
    && apt-get remove -y nodejs npm \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && rm -f /var/www/html/public/storage \
    && rm -rf /var/www/html/public/hot \
    && php artisan storage:link \
    && chown -R www-data:www-data /var/www/html \
    && groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Copy Nginx configuration
COPY docker/nginx/prod.conf /etc/nginx/sites-available/default
COPY docker/nginx/prod.conf /etc/nginx/sites-enabled/default

# Copy and set up startup scripts
COPY docker/scripts/laravel-start.sh /usr/local/bin/laravel-start.sh
COPY docker/php/www.conf /usr/local/etc/php-fpm.d/www.conf

# Expose port 80 for HTTP
EXPOSE 80

# Run as root so the startup script can set permissions
CMD ["/usr/local/bin/laravel-start.sh"]
