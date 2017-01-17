FROM php:5.6-apache

MAINTAINER sasan.rose@gmail.com

RUN apt-get update && apt-get install -y \
	cron \
	gearman-job-server \
	git-core \
	libgearman-dev \
	redis-tools \
	&& pecl install gearman \
	&& docker-php-ext-enable gearman


EXPOSE 80

COPY docker/default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/
COPY docker/start.sh /usr/src/start.sh

WORKDIR /etc/cron.d
COPY docker/crontab phpredmin
RUN chmod 0644 phpredmin

WORKDIR /usr/src

RUN git clone https://github.com/phpredis/phpredis.git
WORKDIR /usr/src/phpredis
# Version 3 has a bug with zAdd so checkout to 2.2.8
RUN git checkout tags/2.2.8 \
	&& phpize \
	&& ./configure \
	&& make \
	&& make install \
	&& docker-php-ext-enable redis

WORKDIR /var/www/html
COPY . phpredmin/

# Clean up
RUN rm -rf /usr/src/phpredis

RUN apt-get --purge remove -y git-core \
	&& apt-get clean

ENV PHPREDMIN_LOG_DRIVER="std"
ENV PHPREDMIN_LOG_THRESHOLD="4"

WORKDIR /var/www/html/phpredmin/public

RUN chmod u+x /usr/src/start.sh
CMD ["/usr/src/start.sh"]
