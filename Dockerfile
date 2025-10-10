FROM php:8.3-fpm

# Instalar dependencias del sistema y PHP
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev libgmp-dev libicu-dev nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip gmp intl \
    && rm -rf /var/lib/apt/lists/*

# Eliminar la configuración por defecto de Nginx
RUN rm -f /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/conf.d/default.conf

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Copiar configuración personalizada de Nginx (desde raíz, no subdir)
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Configuración de PHP-FPM para 127.0.0.1:9000
RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www

# Copiar dependencias PHP primero (para cache)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && node -v \
    && npm -v

# Copiar dependencias frontend primero (para cache)
COPY package*.json ./
RUN npm install
RUN npm rebuild rollup --force || true  # Opcional, con fallback si falla

# Copiar código fuente (después de deps)
COPY . .

# Compilar assets
RUN npm run build || echo "⚠️ npm run build falló (verifica package.json)"

# Copiar script de inicio (desde raíz)
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Permisos para Laravel
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Verificar sintaxis de Nginx (opcional, con fallback)
RUN nginx -t || echo "⚠️ nginx -t falló (config básica OK?)"

RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache storage/logs
RUN mkdir -p bootstrap/cache

# 2. Mover los comandos de permisos aquí, que es el lugar correcto
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Exponer puerto
EXPOSE 80

# Entrypoint
ENTRYPOINT ["/entrypoint.sh"]