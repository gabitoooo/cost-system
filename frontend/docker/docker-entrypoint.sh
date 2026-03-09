#!/bin/sh

# Configurar .env de Vite
if [ ! -f "/app/.env" ]; then
    cp /app/.env.example /app/.env
fi

# Escribe o reemplaza una clave en el .env
set_env() {
    local key="$1" value="$2" file="/app/.env"
    if grep -qE "^#?\s*${key}=" "$file"; then
        sed -i "s|^#\?\s*${key}=.*|${key}=${value}|" "$file"
    else
        echo "${key}=${value}" >> "$file"
    fi
}

set_env "VITE_API_URL" "${VITE_API_URL}"

# Instalar dependencias si no existen
if [ ! -f "node_modules/.bin/vite" ]; then
  echo "node_modules no encontrado, ejecutando npm install..."
  npm install
fi

exec npm run dev
