#bin/bash

chown -R bitnami:daemon /opt/bitnami/apache/htdocs/storage
chmod -R  775 /opt/bitnami/apache/htdocs/storage
find /opt/bitnami/apache/htdocs/storage -type d -exec chmod g+s {} \;