# Etapa 1: build de assets
FROM node:20 AS node_modules

WORKDIR /app
COPY package*.json ./
RUN npm install

# Etapa 2: PHP y Composer
FROM php:8.2-cli AS composer_php

# Instala extensiones necesarias
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    zip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql zip

# Instala Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copia todo el proyecto
COPY . .

# Copia node_modules del primer stage
COPY --from=node_modules /app/node_modules ./node_modules

# Instala dependencias PHP
RUN composer install

# Build assets
RUN npm run build

# Comandos Laravel
RUN php artisan key:generate && \
    php artisan migrate --force && \
    php artisan db:seed --class=UserSeeder && \
    php artisan db:seed --class=InsectSeeder && \
    php artisan optimize && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# Etapa final: runtime
FROM php:8.2-cli

WORKDIR /app

COPY --from=composer_php /app /app

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]