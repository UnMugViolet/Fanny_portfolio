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
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

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
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libonig-dev \
    libpng-dev \
    libxml2-dev \
    libzip-dev \
    unzip \
    zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy package files first for better caching
COPY package*.json ./
COPY composer.json composer.lock ./

# Install dependencies, copy code, build assets, and cleanup
RUN composer install --no-dev --optimize-autoloader --no-interaction \
    && npm ci --only=production

# Copy application code
COPY . .

# Build frontend assets and cleanup
RUN npm run build \
    && npm cache clean --force \
    && rm -rf node_modules \
    && apt-get remove -y nodejs npm \
    && apt-get autoremove -y \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* \
    && chown -R www-data:www-data /var/www/html \
    && groupadd -g 1000 www \
    && useradd -u 1000 -ms /bin/bash -g www www

# Switch to non-root user
USER www

# Expose port 9000 for PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
