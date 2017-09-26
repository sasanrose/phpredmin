#! /bin/bash

# Trap sigkill
trap "pkill gearmand && pkill -WINCH apache2" TERM

cp /root/.htaccess /var/www/html/phpredmin/public

# Run gearman work & Start web server
apache2-foreground
