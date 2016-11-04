PHP Redis Admin
=========

A web interface to manage and monitor your Redis server(s).

This is a maintained fork of [PHPRedMin](https://github.com/sasanrose/phpredmin), by [Sasan Rose](https://github.com/sasanrose) (_Thanks for the great job!_).

_Note:_ PHP Redis Admin is mostly compatible with [phpredis](https://github.com/nicolasff/phpredis) redis module for PHP

## Installation

### Docker

You can use **[docker](https://www.docker.com)** to run PHP Redis Admin:

```Bash
docker run -p 8080:80 -d --name php-redis-admin faktiva/php-redis-admin
```
And then you can just easily point your broswer to [http://localhost:8080](http://localhost:8080)

**Note:**
_You can use **ENV variables** to override any configuration directive of PHP Redis Admin._

Moreover, you can just use **docker compose** to also setup a redis container:

```Yaml
version: '2'
services:
    php-redis-admin:
        image: faktiva/php-redis-admin
        environment:
            - PHPREDMIN_DATABASE_REDIS_0_HOST=redis
        ports:
            - "8080:80"
        depends_on:
            - redis
    redis:
        image: redis
```

### Manual installation

Just drop PHP Redis Admin in your webserver's root directory and point your browser to it (You also need [phpredis](https://github.com/phpredis/phpredis) installed)

## Configuration

**You can copy `app/config/config.dist.php`to `app/config/config.php` and edit as you need. Of course you can also include the original file at the beginning, overriding only the configuration you need and retaining the distribution defaults.**

```php
// app/config/config.php

require_once __DIR__.'/config.dist.php';

$config = array_merge(
    $config,
    array(
		/*
		 * the following are your custom settings ...
		 */
        'debug' => true,
        'auth' => null,
        'log' => array(
            'driver'    => 'file',
            'threshold' => 5, /* 0: Disable Logging, 1: Error, 2: Warning, 3: Notice, 4: Info, 5: Debug */
            'file'      => array('directory' => 'var/logs')
        ),
    )
);

return $config;

```

**Note:**
_If your redis server is on an IP or port other than defaults (`localhost:6379`), you should specify it in your config file._

Apache configuration example (`/etc/httpd/conf.d/phpredmin.conf`):

```ApacheConf
# PHP Redis Admin sample apache configuration
#
# Allows only localhost by default

Alias /phpredmin /var/www/phpredmin/web

<Directory /var/www/phpredmin/>
   AllowOverride All

   <IfModule mod_authz_core.c>
     # Apache 2.4
     <RequireAny>
       Require ip localhost
       Require local
     </RequireAny>
   </IfModule>
   <IfModule !mod_authz_core.c>
     # Apache 2.2
     Order Deny,Allow
     Deny from All
     Allow from 127.0.0.1
     Allow from ::1
   </IfModule>
</Directory>
```

### Basic Authentication

By default, the dashboard is password protected using **Basic Authentication** mechanism, with the default username and password set to `admin`.

You can find the `auth` config section inside the `config.dist.php` file.

**Note:**
You **should** use the `[password_hash()](http://php.net/manual/en/function.password-hash.php)` PHP function with your desired password and store the result in the `password` config key, instead of storing the plaintext password as shown int the distributed config file.


## Features

See [Features.md](Features.md)

## License

See [LICENSE.md](LICENSE.md)

