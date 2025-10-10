#!/bin/sh

# Limpiar cualquier default.conf problemático que Easypanel pueda haber montado
rm -f /etc/nginx/conf.d/default.conf
# Opcional: Si hay más archivos en conf.d, elimínalos todos (pero sé cuidadoso)
# rm -f /etc/nginx/conf.d/*.conf

# Iniciar PHP-FPM en segundo plano
php-fpm

# Probar la configuración de Nginx antes de iniciar
if ! nginx -t; then
    echo "Error en la configuración de Nginx. Saliendo..."
    exit 1
fi

# Iniciar Nginx en primer plano
nginx -g "daemon off;"