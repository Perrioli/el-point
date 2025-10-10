FROM php:8.3-fpm

# Instalar dependencias del sistema (Debian-based)
RUN apt-get update && apt-get install -y \
    git unzip curl libpng-dev libonig-dev libxml2-dev libzip-dev libgmp-dev libicu-dev nginx \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip gmp intl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*  # Limpieza para reducir tamaño

# Eliminar configuraciones por defecto de Nginx (evita conflictos con Easypanel)
RUN rm -f /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/conf.d/default.conf

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer  # Usa composer:2 (no 2.8, para latest estable)

# Copiar configuración personalizada de Nginx (sobrescribe default)
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf  # Asume que nginx.conf está en /docker/ en tu proyecto

# Configuración de PHP-FPM para TCP (coincide con nginx.conf)
RUN sed -i 's/listen = .*/listen = 127.0.0.1:9000/' /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www

# Copiar y instalar dependencias PHP (cache-friendly)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Instalar Node.js temporalmente para build de assets (multi-stage ligera)
# Usamos una stage temporal para Node, pero instalamos en la main para simplicidad
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && npm install -g npm@latest  # Actualiza npm si es necesario

# Copiar y instalar dependencias frontend (cache-friendly)
COPY package*.json ./
RUN npm ci --only=production  # Usa 'ci' para lockfile estricto; --only=production si no necesitas dev para build

# Copiar código fuente y compilar assets
COPY . .
RUN npm run build  # Si falla, agrega 'npm rebuild rollup --force' antes si es necesario
# Opcional: Remover Node post-build para reducir tamaño (pero requiere purge)
# RUN apt-get purge -y nodejs npm && apt-get autoremove -y

# Copiar script de inicio
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Ajustar permisos para Laravel (solo una vez)
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Verificar sintaxis de Nginx en build-time (para depurar temprano)
RUN nginx -t

# Exponer puerto HTTP
EXPOSE 80

# Usar entrypoint como proceso principal
ENTRYPOINT ["/entrypoint.sh"]