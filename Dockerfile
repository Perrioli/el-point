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
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-interaction --no-scripts --no-progress --prefer-dist

# ---- Etapa 3: Imagen Final de Producción ----
FROM php:8.2-fpm-alpine

# Instalar dependencias del sistema y Nginx
RUN apk update && apk add --no-cache nginx

# --- LA CORRECCIÓN PARA EL ERROR DE NGINX ---
# Eliminar la configuración por defecto de Nginx que causa conflictos
RUN rm /etc/nginx/conf.d/default.conf

# Instalar extensiones de PHP para Laravel
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Copiar los archivos de la aplicación y dependencias
COPY --from=php-builder /app/vendor ./vendor
COPY --from=node-builder /app/public ./public
COPY . .

# Copiar archivos de configuración del servidor
COPY nginx.conf /etc/nginx/nginx.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]