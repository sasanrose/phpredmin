FROM php:5.6-apache
MAINTAINER albert@faktiva.com
EXPOSE 80

RUN apt-get update && apt-get install -y --no-install-recommends \
	    cron \
	    gearman-job-server \
	    git-core \
	    libgearman-dev \
    && rm -rf /var/lib/apt/lists/* \
    \
    && pecl install gearman \
    && docker-php-ext-enable gearman \
    && pecl install redis-2.2.8 \
    && docker-php-ext-enable redis

COPY docker/default.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php.ini /usr/local/etc/php/
COPY docker/start.sh /usr/src/start.sh

WORKDIR /etc/cron.d
COPY docker/crontab php-redis-admin
RUN chmod 0644 php-redis-admin

WORKDIR /var/www/html
COPY . php-redis-admin/
RUN mkdir php-redis-admin/logs && chown -R www-data:www-data php-redis-admin/logs

WORKDIR /var/www/html/php-redis-admin/web
RUN chmod u+x /usr/src/start.sh
CMD ["/usr/src/start.sh"]
