#!/bin/sh
# Limpiar cualquier default.conf residual (por si Easypanel monta)
rm -f /etc/nginx/conf.d/default.conf

# Iniciar PHP-FPM en background
php-fpm &

# Probar Nginx
if ! nginx -t; then
    echo "Error en config de Nginx. Saliendo..."
    exit 1
fi

# Iniciar Nginx en foreground
nginx -g "daemon off;"