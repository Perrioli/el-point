#!/bin/sh
set -e

echo "--- Iniciando PHP-FPM en segundo plano ---"
php-fpm

echo "--- Fin del script de inicio (esto no debería pasar si FPM funciona bien) ---"