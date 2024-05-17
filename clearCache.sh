#bin/bash

php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan config:clear
php artisan config:cache

chmod +x artisan
chmod +x *.sh
