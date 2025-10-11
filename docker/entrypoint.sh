#!/bin/sh
set -e  # Salir en error (bueno para depurar)

echo "🔧 Ejecutando migraciones y caches de Laravel..."

# Opcional: Esperar a que la DB esté lista (ajusta si tu DB es externa en Easypanel)
# Puedes usar un wait script si es necesario, pero por ahora, hazlo no-fatal
php artisan migrate --force || echo "⚠️ Migraciones fallaron (DB no lista? Revisa logs)"
php artisan config:cache || echo "⚠️ Config cache falló"
php artisan route:cache || echo "⚠️ Route cache falló"
php artisan view:cache || echo "⚠️ View cache falló"

# Limpiar cualquier config Nginx problemática que Easypanel pueda montar en runtime
rm -f /etc/nginx/conf.d/default.conf
rm -f /etc/nginx/sites-enabled/default

echo "🚀 Iniciando PHP-FPM y Nginx..."

# Iniciar PHP-FPM en background (daemon mode)
php-fpm -D

# Esperar un poco para que PHP-FPM inicie y escuche en 9000
sleep 2

# Verificar configuración de Nginx (falla temprano si hay error, e.g., sintaxis)
if ! nginx -t; then
    echo "❌ Error en la configuración de Nginx. Output:"
    nginx -t
    exit 1
fi

# Iniciar Nginx en foreground (mantiene el contenedor vivo; no uses 'service')
echo "✅ Nginx y PHP-FPM iniciados correctamente."
exec nginx -g "daemon off;"