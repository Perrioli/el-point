#!/bin/sh

# Iniciar PHP-FPM en segundo plano
php-fpm &

# Iniciar Nginx en primer plano para mantener el contenedor activo
nginx -g "daemon off;"