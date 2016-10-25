FROM php:5.6-apache

MAINTAINER sasan.rose@gmail.com

RUN apt-get update && apt-get install -y \
	gearman-job-server \
	git-core \
	libgearman-dev \
	&& pecl install gearman \
	&& docker-php-ext-enable gearman

EXPOSE 80


COPY docker/default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/
COPY docker/start.sh /usr/src/start.sh

WORKDIR /usr/src

RUN git clone https://github.com/phpredis/phpredis.git
WORKDIR /usr/src/phpredis
RUN phpize \
	&& ./configure \
	&& make \
	&& make install \
	&& docker-php-ext-enable redis

WORKDIR /var/www/html
COPY . phpredmin/
RUN mkdir phpredmin/logs && chown www-data:www-data phpredmin/logs -R

WORKDIR /var/www/html/phpredmin/public

RUN chmod u+x /usr/src/start.sh
CMD ["/usr/src/start.sh"]
