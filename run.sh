#!/bin/bash
# Script untuk menjalankan NikelFleet secara lokal di CachyOS / Linux
echo "⚡ Menjalankan NikelFleet - Sistem Monitoring Kendaraan Tambang..."

# Pastikan path php & runtime terbaca
if [ -d "./.php_bin" ]; then
    export PATH="$(pwd)/.php_bin/usr/bin:$PATH"
    export LD_LIBRARY_PATH="$(pwd)/.php_bin/usr/lib:$LD_LIBRARY_PATH"
fi

# Jalankan server Laravel di port 8000
php -c ./.php_bin/php.ini artisan serve --host=127.0.0.1 --port=8000
