# ---- Etapa 1: Compilar los assets de Frontend ----
FROM node:20-alpine AS node-builder

# Recibir build-args de Easypanel y setear como ENV para Vite/npm
ARG VITE_APP_NAME
ARG ASSET_URL
ENV VITE_APP_NAME=$VITE_APP_NAME
ENV ASSET_URL=$ASSET_URL
# Agrega más si las usas, e.g., ARG APP_NAME; ENV APP_NAME=$APP_NAME (para fallback)

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci --only=production  # <- MEJOR: Usa 'ci' para lockfile estricto y solo prod si es posible

# Copia solo lo necesario para build (evita copiar todo al inicio para cache)
COPY resources/ ./resources/  # <- AGREGADO: Copia explícitamente resources para assets
COPY vite.config.js tailwind.config.js ./.  # <- AGREGADO: Copia configs de Vite/Tailwind si existen
COPY . .

# Opcional: Si usas paquetes nativos, instala dependencias del sistema
# RUN apk add --no-cache python3 make g++  # Descomenta si npm install falla por compilación (e.g., canvas, sharp)

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

# Eliminar la configuración por defecto de Nginx que causa conflictos
RUN rm -f /etc/nginx/conf.d/default.conf

# Instalar extensiones de PHP para Laravel
RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Copiar los archivos de la aplicación y dependencias
COPY --from=php-builder /app/vendor ./vendor
COPY --from=node-builder /app/public ./public  # Assets compilados van aquí

# Copia el resto (después de assets para cache)
COPY . .

# Copiar archivos de configuración del servidor
COPY nginx.conf /etc/nginx/nginx.conf
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Verificar sintaxis de Nginx en build time
RUN nginx -t

# Ajustar permisos para Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]