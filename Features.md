PHP Redis Admin
=========

## Features

### Multi-Server functionality

You can add as many redis servers as you want to your config.php file and choose between the defined servers from the menu available on the left side of all pages in PHP Redis Admin:

![](http://dl.dropbox.com/u/5413590/phpredmin/multiserver.png)

We must credit [Eugene Fidelin](https://github.com/eugef) for his great contributions to implement this feature

### Statistics

_Note:_ If you want this feature to work, you have to setup the cron to gather data from your redis server as follows:

```
* * * * * root cd /var/www/phpredmin/web && php index.php cron/index
```

#### Memory

![](http://dl.dropbox.com/u/5413590/phpredmin/memory.jpg)

#### CPU And Clients

![](http://dl.dropbox.com/u/5413590/phpredmin/cpu.jpg)

#### Keys and Connections

![](http://dl.dropbox.com/u/5413590/phpredmin/keyspace.jpg)

#### Databases

![](http://dl.dropbox.com/u/5413590/phpredmin/dbkeys.jpg)

### Console

PHP Redis Admin provides you with a web-base redis console. This functionality takes advantage of PHP's `exec` function. Although, all the commands are escaped for security, you can disable terminal from configuration file. In addition, you can set history limit or disable history by setting it to 0:

![](http://dl.dropbox.com/u/5413590/phpredmin/console.jpg)

### Info

Information about your redis setup

![](http://dl.dropbox.com/u/5413590/phpredmin/info.jpg)

### Configurations

View your redis runtime configurations

![](http://dl.dropbox.com/u/5413590/phpredmin/config.jpg)

### Slowlog

Find slow redis commands

_Note:_ PHP Redis Admin uses eval to fetch slow log. So to use this feature you need redis version >= 2.6.0

![](http://dl.dropbox.com/u/5413590/phpredmin/slowlog.jpg)

### Database Manipulation

You can easily switch between databases belonging to different servers easily:

![](http://dl.dropbox.com/u/5413590/phpredmin/multiserver.png)

You can flush selected database or all databases. You can also save database to a file on disk:

![](http://dl.dropbox.com/u/5413590/phpredmin/actions.jpg)

### Key-Value Manipulation

#### Search

The search box will let you to easily search keys in the selected database:
_Note:_ Becareful, since this still doesn't support pagination, try to limit your search otherwise if your search result is too long (e.g. `*`) then your browser might crash.

![](http://dl.dropbox.com/u/5413590/phpredmin/search.jpg)

The search results will be shown to you as a table. In this table besides the basic information about each key, PHP Redis Admin provides you with some actions:

* Expire (Sets TTL for a key)
* View (Shows keys' value/values and lets you manipulate it/them)
* Rename
* Move (Moves key to another database)
* Delete

![](http://dl.dropbox.com/u/5413590/phpredmin/results.jpg)

#### Add key-Value

From the main page of PHP Redis Admin you can add different types of key-values.

##### Strings

![](http://dl.dropbox.com/u/5413590/phpredmin/addstring.jpg)

##### Hashes

![](http://dl.dropbox.com/u/5413590/phpredmin/addhash.jpg)

##### Lists

![](http://dl.dropbox.com/u/5413590/phpredmin/addlist.jpg)

##### Sets

![](http://dl.dropbox.com/u/5413590/phpredmin/addset.jpg)

##### Sorted Sets

![](http://dl.dropbox.com/u/5413590/phpredmin/addzset.jpg)

### View keys' values

PHP Redis Admin makes it easier for you to manage your lists, hashes, sets and sorted sets. After searching for a special key, you can choose view action to see the contents of that key (According to its type) and manipulate them.

#### Lists

_Note:_ This supports pagination

![](http://dl.dropbox.com/u/5413590/phpredmin/listresult.jpg)

#### Hashes

![](http://dl.dropbox.com/u/5413590/phpredmin/hashresult.jpg)

#### Sets

_Note:_ This supports pagination

_Note:_ Thanks to [Ahmed Hamdy](https://github.com/ahmed-hamdy90) you can now edit members of a set

![](http://dl.dropbox.com/u/5413590/phpredmin/setresult.jpg)

#### Sorted Sets

_Note:_ This supports pagination

![](http://dl.dropbox.com/u/5413590/phpredmin/zsetresult.jpg)

### Bulk Actions

#### Bulk Delete

This feature lets you delete a key or a bunch of keys using wild cards

![](http://dl.dropbox.com/u/5413590/phpredmin/bulk-delete.png)

![](http://dl.dropbox.com/u/5413590/phpredmin/bulk-delete-progress.png)

_Note:_ This feature needs gearman. You have to both install gearman and php gearman extension

#### Gearman Worker

You can run gearman worker using the following command:

```bash
php index.php gearman/index
```
You can also setup a service for this command. I prefer supervisord to make it always running. Here is my config file:

```ini
[program:phpredmin]
directory=/var/www/phpredmin/web
command=php index.php gearman/index
process_name=%(program_name)s
numprocs=1
stdout_logfile=/var/log/supervisor/phpredmin.log
autostart=true
autorestart=true
user=sasan
```

