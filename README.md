PHPRedMin
=========

PHP Administrator Panel for REDIS. PHPRedMin is a simple web panel to manage your redis database from web.

## Technologies Used

![Nanophp](https://github.com/sasanrose/NanoPHP) framework

![phpredis](https://github.com/nicolasff/phpredis) redis module for PHP

![Bootstrap](http://twitter.github.com/bootstrap) front-end framework

![JQuery](http://jquery.com/) JavaScript library

![Nvd3](https://github.com/novus/nvd3) reusable chart library for d3.JS

## Installation

Just drop phpredmin in your webserver's root directory and point your browser to it (You also need [phpredis](https://github.com/nicolasff/phpredis) installed)

## Features

### Statistics

Note: If you want this feature to work, you have to setup the cron to gather data from your redis server as follows:

```bash
* * * * * root cd /var/www/phpmyredis/public && php index.php cron/index
```

#### Memory

![](http://dl.dropbox.com/u/5413590/phpredmin/memory.jpg)

#### CPU And Clients

![](http://dl.dropbox.com/u/5413590/phpredmin/cpu.jpg)

#### Keys and Connections

![](http://dl.dropbox.com/u/5413590/phpredmin/keyspace.jpg)

#### Databases

![](http://dl.dropbox.com/u/5413590/phpredmin/dbkeys.jpg)

### Info

Information about your redis setup

![](http://dl.dropbox.com/u/5413590/phpredmin/info.jpg)

### Configurations

View your redis runtime configurations

![](http://dl.dropbox.com/u/5413590/phpredmin/config.jpg)

### Slowlog

Find slow redis commands

Note: PHPRedMin uses eval to fetch slow log. So to use this feature you need redis version >= 2.6.0

![](http://dl.dropbox.com/u/5413590/phpredmin/slowlog.jpg)

### Database Manipulation

You can switch between databases easily:

![](http://dl.dropbox.com/u/5413590/phpredmin/dbselect.jpg)

You can flush selected database or all databases. You can also save database to a file on disk:

![](http://dl.dropbox.com/u/5413590/phpredmin/actions.jpg)

### Key-Value Manipulation

#### Search

The search box will let you to easily search keys in the selected database:

![](http://dl.dropbox.com/u/5413590/phpredmin/search.jpg)

The search results will be shown to you as a table. In this table besides the basic information about each key, PHPRedMin provides you with some actions:

* Expire (Sets TTL for a key)
* View (Shows keys' value/values and lets you manipulate it/them)
* Rename
* Move (Moves key to another database)
* Delete

![](http://dl.dropbox.com/u/5413590/phpredmin/results.jpg)
