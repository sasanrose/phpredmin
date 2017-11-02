#! /bin/bash

if [ ! -d /run/secrets/ ]; then
    if [ ! -f /secrets/phpredmin_session_key ]; then
        echo $(openssl rand -base64 32) >> /secrets/phpredmin_session_key
    fi

    if [ ! -f /secrets/phpredmin_ssl_key.pem ] || [ ! -f /secrets/phpredmin_ssl_cert.pem ]; then
        SUBJ="/C=AU/ST=NSW/L=Sydney/O=PHPRedmin/OU=Redis/CN=phpredmin.local"
        $(openssl req -nodes -new -x509 -keyout /secrets/phpredmin_ssl_key.pem -out /secrets/phpredmin_ssl_cert.pem -days 365 -subj "$SUBJ")
    fi
else
    rm -rf /secrets/*
    $(ln -s /run/secrets/* /secrets/)
fi

# Trap sigkill
trap "pkill gearmand && pkill -WINCH apache2" TERM

cp /root/.htaccess /var/www/html/phpredmin/public

# Run gearman work & Start web server
apache2-foreground
