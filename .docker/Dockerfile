FROM php:7.0-apache

MAINTAINER sasan.rose@gmail.com

EXPOSE 80

RUN apt-get update \
    && apt-get install -y zlib1g-dev openssl \
    && pecl install redis-3.1.2 \
    && docker-php-ext-enable redis \
    && pecl install raphf-2.0.0 \
    && docker-php-ext-enable raphf \
    && pecl install propro-2.0.1 \
    && docker-php-ext-enable propro \
    && pecl install pecl_http-3.1.0 \
    && docker-php-ext-enable http \
    && docker-php-ext-install gettext \
    && a2enmod rewrite

COPY .docker/apache/default.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html/phpredmin

WORKDIR /var/www/html/phpredmin

COPY .docker/apache/.htaccess /root/.htaccess
COPY .docker/start.sh /root/start.sh

RUN chmod u+x /root/start.sh

CMD ["/root/start.sh"]
