#! /bin/bash

if [ ! -f /run/secrets/phpredmin_session_key ]; then
    echo "Generating PHPRedmin secrets"
    echo $(openssl rand -base64 32) >> /secrets/phpredmin_session_key
fi

if [ ! -f /run/secrets/phpredmin_ssl_key.pem ] && [ ! -f /run/secrets/phpredmin_ssl_cert.pem ]; then
    echo "Generating PHPRedmin SSL certificates"
    SUBJ="/C=AU/ST=NSW/L=Sydney/O=PHPRedmin/OU=Redis/CN=phpredmin.local"
    $(openssl req -nodes -new -x509 -keyout /secrets/phpredmin_ssl_key.pem -out /secrets/phpredmin_ssl_cert.pem -days 365 -subj "$SUBJ")
fi

if [ -d /run/secrets/ ]; then
    $(ln -s /run/secrets/* /secrets/)
fi

# Trap sigkill
trap "pkill gearmand && pkill -WINCH apache2" TERM

cp /root/.htaccess /var/www/html/phpredmin/public

# Run gearman work & Start web server
apache2-foreground
