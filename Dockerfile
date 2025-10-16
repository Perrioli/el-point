# Usar imagen base de PHP 8.3 con FPM (basada en Debian)
FROM php:8.3-fpm

# 1. INSTALAR DEPENDENCIAS DEL SISTEMA Y EXTENSIONES DE PHP
# Se agrupan todas las instalaciones en una sola capa para optimizar
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    nginx \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libgmp-dev \
    libicu-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip gmp intl

# 2. INSTALAR NODE.JS Y NPM
# Se instala la versión 20 de Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 3. INSTALAR COMPOSER
# Se copia Composer globalmente desde su imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. CONFIGURAR NGINX Y PHP-FPM
# Se elimina la configuración por defecto de Nginx
RUN rm -f /etc/nginx/sites-enabled/default
# Se copia nuestra configuración personalizada
COPY nginx.conf /etc/nginx/nginx.conf
# Se asegura que PHP-FPM escuche en el puerto 9000
COPY docker/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

# 5. ESTABLECER DIRECTORIO DE TRABAJO
WORKDIR /var/www

# 6. INSTALAR DEPENDENCIAS DE PHP (Cacheable)
# Copiamos solo los archivos de dependencias para aprovechar el caché de Docker
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 7. INSTALAR DEPENDENCIAS DE JAVASCRIPT (Cacheable)
COPY package*.json ./
RUN npm install

# 8. COPIAR TODO EL CÓDIGO DE LA APLICACIÓN
COPY . .

# 9. COMPILAR ASSETS DE FRONTEND
# Se ejecuta después de copiar todo el código
RUN npm run build

# 10. CREAR CARPETAS Y ESTABLECER PERMISOS
# Se crean las carpetas que Laravel necesita para escribir
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs bootstrap/cache
# Se establecen los permisos una sola vez, al final, sobre las carpetas ya creadas
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

# 11. EXPONER PUERTO Y EJECUTAR SCRIPT DE INICIO
EXPOSE 80
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
ENTRYPOINT ["/entrypoint.sh"]