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
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer  # Usa composer:2 (latest estable, no 2.8 específica)

# Copiar configuración personalizada de Nginx
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Configuración de PHP-FPM para que escuche en 127.0.0.1:9000 (agregado IP para bind local)
RUN sed -i 's|listen = .*|listen = 127.0.0.1:9000|' /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www

# Copiar y preparar dependencias PHP (cache-friendly: primero composer.json)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && node -v \
    && npm -v

# Copiar y preparar dependencias frontend (cache-friendly: primero package.json)
COPY package*.json ./
RUN npm install
RUN npm rebuild rollup --force  # Si lo necesitas para Vite/Rollup

# Copiar código fuente y compilar assets (después de deps para cache)
COPY . .

# Compilar assets
RUN npm run build

# Copiar script de inicio
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Permisos para Laravel (solo una vez, al final)
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Verificar sintaxis de Nginx en build-time (agregado para depurar temprano)
RUN nginx -t

# Exponer puerto HTTP
EXPOSE 80

# Usar entrypoint como proceso principal
ENTRYPOINT ["/entrypoint.sh"]