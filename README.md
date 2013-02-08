PHPRedMin
=========

PHP Administrator Panel for REDIS. PHPRedMin is a simple web panel to manage your redis database from web.

## Technologies Used

[Nanophp](https://github.com/sasanrose/NanoPHP) framework
[phpredis](https://github.com/nicolasff/phpredis) redis module for PHP
[Bootstrap](http://twitter.github.com/bootstrap) front-end framework
[JQuery](http://jquery.com/) JavaScript library
[Nvd3](https://github.com/novus/nvd3) reusable chart library for d3.JS

## Installation

Just drop phpredmin in your webserver's root directory and point your browser to it (You also need [phpredis](https://github.com/nicolasff/phpredis) installed)

## Features

### Statistics

Note: If you want this feature to work, you have to setup the cron to gather data from your redis server as follows:

```bash
* * * * * root cd /var/www/phpmyredis/public && php index.php cron/index
```

#### Memory

![](http://dl.dropbox.com/u/5413590/memoryphpredmin.jpg)

#### CPU And Clients

![](http://dl.dropbox.com/u/5413590/cpuphpredmin.jpg)

#### Keys and Connections

![](http://dl.dropbox.com/u/5413590/keyspacephpredmin.jpg)

#### Databases

![](http://dl.dropbox.com/u/5413590/dbkeysphpredmin.jpg)
