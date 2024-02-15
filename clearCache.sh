#bin/bash

php /opt/bitnami/apache/htdocs/artisan cache:clear
php /opt/bitnami/apache/htdocs/artisan route:clear
php /opt/bitnami/apache/htdocs/artisan view:clear
php /opt/bitnami/apache/htdocs/artisan config:clear
php /opt/bitnami/apache/htdocs/artisan config:cache

chown -R bitnami:daemon /opt/bitnami/apache/htdocs/* 
chown -R bitnami:daemon /opt/bitnami/apache/htdocs/storage/framework/cache
chown -R bitnami:daemon /opt/bitnami/apache/htdocs/bootstrap/cache
chown -R bitnami:daemon /opt/bitnami/apache/htdocs/storage
chmod -R  775 /opt/bitnami/apache/htdocs/storage
find /opt/bitnami/apache/htdocs/storage -type d -exec chmod g+s {} \;
chmod +x /opt/bitnami/apache/htdocs/artisan
chmod +x /opt/bitnami/apache/htdocs/*.sh
