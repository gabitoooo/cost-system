#!/bin/bash
set -e

# Instalar dependencias si falta vendor
if [ ! -d "/var/www/html/vendor" ]; then
    echo "==> Instalando dependencias de Composer..."
    composer install --no-interaction --optimize-autoloader --working-dir=/var/www/html
fi

# Configurar .env de Laravel
if [ ! -f "/var/www/html/.env" ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Escribe o reemplaza una clave en el .env (maneja claves comentadas e inexistentes)
set_env() {
    local key="$1" value="$2" file="/var/www/html/.env"
    if grep -qE "^#?\s*${key}=" "$file"; then
        sed -i "s|^#\?\s*${key}=.*|${key}=${value}|" "$file"
    else
        echo "${key}=${value}" >> "$file"
    fi
}

set_env "DB_CONNECTION" "pgsql"
set_env "DB_HOST"       "${DB_HOST}"
set_env "DB_PORT"       "${DB_PORT}"
set_env "DB_DATABASE"   "${DB_DATABASE}"
set_env "DB_USERNAME"   "${DB_USERNAME}"
set_env "DB_PASSWORD"   "${DB_PASSWORD}"

# Generar APP_KEY si está vacía o ausente
if ! grep -qE "^APP_KEY=.+" /var/www/html/.env; then
    echo "==> Generando APP_KEY..."
    php /var/www/html/artisan key:generate --force
fi

# Ejecutar migraciones
echo "==> Ejecutando migraciones..."
php /var/www/html/artisan migrate --force

# Ejecutar seeders solo si la BD está vacía (sin usuarios)
echo "==> Verificando seeders..."
USER_COUNT=$(php /var/www/html/artisan tinker --execute="echo \App\Infrastructure\Persistence\Models\User::count();" 2>/dev/null | tail -1)
if [ "$USER_COUNT" = "0" ]; then
    echo "==> Ejecutando seeders..."
    php /var/www/html/artisan db:seed --force
else
    echo "==> Seeders omitidos (BD ya tiene datos)."
fi

# Permisos de storage
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "==> Iniciando PHP-FPM..."
exec "$@"
