#!/bin/bash
# NikelFleet Runner Script
echo "⚡ Menjalankan NikelFleet..."

BASE_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

# Pastikan PHP membaca konfigurasi php.ini untuk ekstensi pdo_sqlite
if [ -d "$BASE_DIR/.php_bin" ]; then
    export PATH="$BASE_DIR/.php_bin/usr/bin:$PATH"
    export PHPRC="$BASE_DIR/.php_bin/php.ini"
    export LD_LIBRARY_PATH="$BASE_DIR/.php_bin/usr/lib:${LD_LIBRARY_PATH}"
elif [ -d "$HOME/.local/php_pkg/etc" ]; then
    export PHPRC="$HOME/.local/php_pkg/etc"
    export LD_LIBRARY_PATH="$HOME/.local/php_pkg/usr/lib:${LD_LIBRARY_PATH}"
fi

php artisan serve --host=127.0.0.1 --port=8000
