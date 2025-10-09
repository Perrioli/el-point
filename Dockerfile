# ---- Etapa 1: Compilar los assets de Frontend ----
FROM node:20-alpine AS node-builder

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm install
COPY . .
RUN npm run build

# ---- Etapa 2: Instalar las dependencias de Backend ----
FROM composer:2 AS php-builder

WORKDIR /app
COPY --from=node-builder /app/vendor ./vendor
COPY database/ database/
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --no-progress --prefer-dist --optimize-autoloader

# ---- Etapa 3: Imagen Final de Producción ----
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y extensiones de PHP para Laravel
RUN apk update && apk add --no-cache \
    nginx \
    supervisor \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql zip gd bcmath exif

WORKDIR /var/www/html

# Copiar los archivos de la aplicación desde las etapas anteriores
COPY --from=php-builder /app .
COPY --from=node-builder /app/public/build ./public/build

# Copiar archivos de configuración del servidor
COPY nginx.conf /etc/nginx/nginx.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]