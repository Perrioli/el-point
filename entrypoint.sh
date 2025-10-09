#!/bin/sh

# Iniciar el servicio PHP-FPM en segundo plano
php-fpm &

# Iniciar Nginx en primer plano (para que el contenedor no se detenga)
nginx -g "daemon off;"