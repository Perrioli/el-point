#!/bin/sh

# Iniciar PHP-FPM en segundo plano
php-fpm &

# Iniciar Nginx en primer plano (esto mantiene el contenedor corriendo)
nginx -g "daemon off;"