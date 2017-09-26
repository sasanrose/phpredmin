FROM php:7.0-apache

MAINTAINER sasan.rose@gmail.com

EXPOSE 80

RUN pecl install redis-3.1.2 \
    && docker-php-ext-enable redis \
    && a2enmod rewrite

COPY .docker/apache/default.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/phpredmin

WORKDIR /var/www/html/phpredmin

COPY .docker/apache/.htaccess /root/.htaccess
COPY .docker/start.sh /root/start.sh

RUN chmod u+x /root/start.sh

CMD ["/root/start.sh"]
