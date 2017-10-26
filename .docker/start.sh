#! /bin/bash

if [ -z $PHPREDMIN_SESSION_KEY ]; then
    export PHPREDMIN_SESSION_KEY=$(openssl rand -base64 32)
fi

# Trap sigkill
trap "pkill gearmand && pkill -WINCH apache2" TERM

cp /root/.htaccess /var/www/html/phpredmin/public

# Run gearman work & Start web server
apache2-foreground
