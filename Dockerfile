# ---- Etapa 1: Compilar los assets de Frontend ----
FROM node:20-alpine AS node-builder

# Recibir build-args de Easypanel y setear como ENV para Vite/npm
ARG VITE_APP_NAME
ARG ASSET_URL
ENV VITE_APP_NAME=$VITE_APP_NAME
ENV ASSET_URL=$ASSET_URL

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci  # Sin --only=production por ahora, para asegurar que dev deps (como vite) se instalen

# Copia explícita y segura: Solo si existen, para evitar paths inválidos
COPY resources/ ./resources/  # Assets JS/CSS
# Copia configs solo si existen (Docker ignora si no, pero para cache, usa múltiples líneas)
COPY vite.config.js ./vite.config.js || true  # <- AGREGADO: || true ignora error si no existe
COPY tailwind.config.js ./tailwind.config.js || true
COPY postcss.config.js ./postcss.config.js || true  # Si usas PostCSS
COPY . .  # Copia el resto (esto sobrescribe si es necesario)

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
COPY --from=node-builder /app/public ./public

# Copia el resto (después de assets para mejor cache)
COPY . .

# Copiar archivos de configuración del servidor (sobrescribe si hay conflictos)
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