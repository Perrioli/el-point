#!/bin/sh
set -e

echo "🔧 Ejecutando migraciones y caches de Laravel..."

# Opcional: Esperar a que la DB esté lista
# sleep 5

php artisan migrate --force || echo "⚠️ Migraciones fallaron (DB no lista?)"
php artisan config:cache || echo "⚠️ Config cache falló"
php artisan route:cache || echo "⚠️ Route cache falló"
php artisan view:cache || echo "⚠️ View cache falló"

# Limpiar configs Nginx problemáticas
rm -f /etc/nginx/conf.d/default.conf
rm -f /etc/nginx/sites-enabled/default

echo "🚀 Iniciando PHP-FPM y Nginx..."

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Iniciar PHP-FPM en background
php-fpm -D

# Esperar a que PHP-FPM inicie
sleep 2

# Verificar Nginx
if ! nginx -t; then
    echo "❌ Error en Nginx. Output:"
    nginx -t
    exit 1
fi

echo "✅ Nginx y PHP-FPM iniciados."
exec nginx -g "daemon off;"